<?php

namespace App\Http\Controllers;

use App\Models\Consultancy;
use Illuminate\Http\Request;
use App\Models\ConsultingReport;

class ConsultancyController extends Controller
{
    
    public function index()
    {
        //get all consulting reports from the authenticated user
        $relatorios = ConsultingReport::where('user_id', auth()->user()->id)->get();
        
        //if relatorios is empty, return response nenhum relatorio cadastrado
        if($relatorios->isEmpty()){
            return response()->json([
                'message' => 'Nenhum relatório cadastrado'
            ], 404);
        }

        //get all consultancies related to the consulting reports
        $consultorias = Consultancy::whereIn('consulting_report_id', $relatorios->pluck('id'))->get();
        return $consultorias;
        
    }
    

    public function store(Request $request)
    {
        //if user is not admin return response
        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => 'Você não tem permissão para cadastrar consultorias'
            ], 403);
        }

        //get if there is any consulting reports related to the user authenticated
        $relatorios = ConsultingReport::where('user_id', auth()->user()->id)->first();

        //validate the request
        $request->validate([
            'objetivo' => ['required', 'string', 'max:2555'],
            'data_reuniao' => ['required', 'date'],
            'forma_contato' => ['required', 'string', 'in:presencial,online,telefone'],
            'status' => ['required', 'string', 'in:Agendado,Realizado,Cancelado,Reagendado,Em andamento,Finalizado,Em espera,Em atraso,Em aberto,Em análise'],
            'consulting_report_id' => ['required', 'integer', 'exists:consulting_reports,id'],
        ]);

        //if the request fails return response with errors
        if(!$request){
            return response()->json([
                'message' => 'Erro ao cadastrar consultoria',
                'errors' => $request->errors()
            ], 400);
        }

        //create a new consultancy
        $consultoria = Consultancy::create([
            'objetivo' => $request->objetivo,
            'data_reuniao' => $request->data_reuniao,
            'forma_contato' => $request->forma_contato,
            'status' => $request->status,
            'consulting_report_id' => $request->consulting_report_id,
            'consultant_id' => $relatorios->consultant_id,
        ]);

        //if the consultancy is created, return response with the consultancy
        if($consultoria){
            return response()->json([
                'message' => 'Consultoria cadastrada com sucesso',
                'consultoria' => $consultoria
            ], 201);
        }

        //if the consultancy is not created, return response with error
        return response()->json([
            'message' => 'Erro ao cadastrar consultoria'
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultancy $consultancy)
    {
        //return the selected consultancy
        return $consultancy;
    }

    public function update(Request $request, Consultancy $consultancy)
    {
        //get if there is any consulting reports related to the user authenticated
        $relatorios = ConsultingReport::where('user_id', auth()->user()->id)->first();

        //validate the request, the attributes are not required
        $request->validate([
            'objetivo' => ['string', 'max:2555'],
            'data_reuniao' => ['date'],
            'forma_contato' => ['string', 'in:presencial,online,telefone'],
            'status' => ['string', 'in:Agendado,Realizado,Cancelado,Reagendado,Em andamento,Finalizado,Em espera,Em atraso,Em aberto,Em análise'],
        ]);

        //if the request fails return response with errors
        if(!$request){
            return response()->json([
                'message' => 'Erro ao atualizar consultoria',
                'errors' => $request->errors()
            ], 400);
        }

        //update the consultancy, if the request isset update with the request attribute, if not use the old value
        $consultancy->update([
            'objetivo' => isset($request->objetivo) ? $request->objetivo : $consultancy->objetivo,
            'data_reuniao' => isset($request->data_reuniao) ? $request->data_reuniao : $consultancy->data_reuniao,
            'forma_contato' => isset($request->forma_contato) ? $request->forma_contato : $consultancy->forma_contato,
            'status' => isset($request->status) ? $request->status : $consultancy->status,
            'consultant_id' => isset($relatorios->consultant_id) || $relatorios->id !== $consultancy->consultant_id ? $relatorios->id : $consultancy->consultant_id,
        ]);

        //if the consultancy is updated, return response with the consultancy
        if($consultancy){
            return response()->json([
                'message' => 'Consultoria atualizada com sucesso',
                'consultoria' => $consultancy
            ], 200);
        }

        //if the consultancy is not updated, return response with error
        return response()->json([
            'message' => 'Erro ao atualizar consultoria'
        ], 400);
    }

    public function destroy(Consultancy $consultancy)
    {
        //if the user authenticated is not admin return response
        if(!auth()->user()->admin){
            return response()->json([
                'message' => 'Você não tem permissão para deletar consultorias'
            ], 403);
        }

        //delete the selected consultancy
        $consultancy->delete();

        //if the consultancy is deleted, return response with message
        if($consultancy){
            return response()->json([
                'message' => 'Consultoria deletada com sucesso'
            ], 200);
        }

        //if the consultancy is not deleted, return response with error
        return response()->json([
            'message' => 'Erro ao deletar consultoria'
        ], 400);
    }
}
