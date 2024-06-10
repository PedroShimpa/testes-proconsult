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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ChamadosController extends Controller
{

    protected $chamado;
    protected $chamadoArquivo;
    protected $chamadoResposta;
    protected $user;

    public function __construct(Chamado $chamado, ChamadoArquivo $chamadoArquivo, ChamadoResposta $chamadoResposta, User $user)
    {
        $this->middleware('auth:api')->except(['downloadFile']);
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
            $data = $request->validated();
            $createChamado = $this->chamado->create($data);
            $this->sendMailToAdmins($createChamado->id);
            return response()->json($createChamado);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['msg' => 'Um erro inesperado occoreu ao criar o chamado'], 500);
        }
    }

    public function sendFiles(Request $request, $chamadoId)
    {
        try {
            $data = $request->only('anexed_files');
            if (!empty($data['anexed_files'])) {
                $anexedFiles = $data['anexed_files'];

                if (!is_array($anexedFiles)) {
                    $anexedFiles = [$anexedFiles];
                }
                foreach ($anexedFiles as  $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $dataInsertArquivo = [
                        'chamado_id' => $chamadoId,
                        'filename' => $file->getClientOriginalName(),
                        'file' => $file->move(public_path('uploads'), $name)
                    ];
                    $this->chamadoArquivo->create($dataInsertArquivo);
                }
                return response()->json('arquivos enviados', 200);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json('erro ao enviar arquivos', 500);
        }
    }

    private function sendMailToAdmins($chamadoId)
    {
        foreach ($this->user->getAdmins() as $user) {
            try {
                Mail::to($user->email)->send(new NovoChamadoMail($chamadoId, $user->name));
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }

    public function downloadFile()
    {
        $file = request()->input('file');
        return response()->download($file);
    }
}
