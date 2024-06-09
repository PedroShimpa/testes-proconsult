<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChamadoRequest;
use App\Http\Requests\CreateChamadoRespostaRequest;
use App\Mail\NovoChamadoMail;
use App\Models\Chamado;
use App\Models\ChamadoArquivo;
use App\Models\ChamadoResposta;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ChamadosController extends Controller
{

    protected $chamado;
    protected $chamadoArquivo;
    protected $chamadoResposta;
    protected $user;

    public function __construct(Chamado $chamado, ChamadoArquivo $chamadoArquivo, ChamadoResposta $chamadoResposta, User $user)
    {
        $this->middleware('auth:api');
        $this->chamado = $chamado;
        $this->chamadoArquivo = $chamadoArquivo;
        $this->chamadoResposta = $chamadoResposta;
        $this->user = $user;
    }

    /**
     * se o usuario for um cliente, ira trazer somente os proprios chamados, se for um admin ou colaborador irá todos os chamados 
     */
    public function index()
    {
        if (auth()->user()->type == "C") {
            return response()->json($this->chamado->getForClient());
        }
        if (auth()->user()->type == "A") {
            return response()->json($this->chamado->getForAdmin());
        }
        return response()->json(['msg' => 'Não foi possível consultar os dados, tente novamente mais tarde'], 501);
    }

    public function getById($id)
    {
        if (auth()->user()->type == "C") {
            $chamado = $this->chamado->getForClient($id);
        }
        if (auth()->user()->type == "A") {
            $chamado = $this->chamado->getForAdmin($id);
        }
        if (!empty($chamado)) {
            $chamado = $chamado->first();
            $chamado->arquivos = $this->chamadoArquivo->getByChamadoId($id);
            $chamado->respostas = $this->chamadoResposta->getByChamadoId($id);
            return response()->json($chamado);
        }
        return response()->json(['msg' => 'Chamado não encontrado'], 404);
    }

    /**
     * responde o chamado
     */
    public function replyChamado(CreateChamadoRespostaRequest $request)
    {
        try {
            $data = $request->validated();
            $created = $this->chamadoResposta->create($data);
            if (auth()->user()->type == 'A') {
                $this->chamado->updateByid($data['chamado_id'], ['status' => 'EA']);
            }
            return !empty($created) ? response()->json($created) : response()->json(['msg' => 'Um erro inesperado occoreu ao responder o chamado'], 500);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['msg' => 'Um erro inesperado occoreu ao responder o chamado'], 500);
        }
    }

    public function finishChamado($id)
    {
        try {
            $updated = $this->chamado->updateByid($id, ['status' => 'F']);
            return !empty($updated) ? response()->json($updated) : response()->json(['msg' => 'Um erro inesperado occoreu ao finalizar o chamado'], 500);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['msg' => 'Um erro inesperado occoreu ao finalizar o chamado'], 500);
        }
    }

    public function store(CreateChamadoRequest $request)
    {
        try {
            //usei a transação para garantir que todas as ações devem ser executas ou tudo deve ser cancelado
            DB::beginTransaction();

            $data = $request->validated();
            $createChamado = $this->chamado->create($data);

            //apos criar o chamado, pega todos os arquivos enviados anteriormente e os incorpora no chamado
            if (!empty($createChamado) && !empty($data['anexed_files'])) {
                foreach ($data['anexed_files'] as $file) {
                    $dataInsertArquivo = [
                        'chamado_id' => $createChamado->id,
                        'filename' => $file->getClientOriginalName(),
                        'file' => Storage::put(uniqid() . $file->getClientOriginalExtension(), $file)
                    ];
                    $this->chamadoArquivo->create($dataInsertArquivo);
                }
            }
            $this->sendMailToAdmins($createChamado->id);

            DB::commit();
            return response()->json($createChamado);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['msg' => 'Um erro inesperado occoreu ao criar o chamado'], 500);
        }
    }

    private function sendMailToAdmins($chamadoId)
    {
        foreach ($this->user->getAdmins() as $user) {
            Mail::to($user->email)->send(new NovoChamadoMail($chamadoId, $user->name));
        }
    }
}
