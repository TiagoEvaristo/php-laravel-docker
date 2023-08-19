<?php

namespace App\Http\Controllers;

use App\Models\ConsultingReport;
use Illuminate\Http\Request;

class ConsultingReportController extends Controller
{
    public function index()
    {
        //return all user authenticated corresponding consulting resports

        return ConsultingReport::where('user_id', auth()->user()->id)->get();
    }

    public function store(Request $request)
    {
        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => 'Você não tem permissão para acessar essa rota'
            ], 403);
        }

        //validate the request received
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'consultant_id' => 'required|exists:consultants,id',
            'relatorio' => 'required|max:2555',
            'meta_corte' => 'required|numeric|min:0',
            'valor_estimado_ganho' => 'required|numeric|min:0',
            'contas_corte' => 'required|numeric|min:0',
        ]);

        //verify if the the request is valid, if not return a error message
        if (!$request) {
            return response()->json([
                'message' => 'Invalid request',
                'errors' => $request->errors()
            ], 400);
        }

        //create a new consulting report
        $consultingReport = ConsultingReport::create([
            'user_id' => $request->user_id,
            'consultant_id' => $request->consultant_id,
            'relatorio' => $request->relatorio,
            'meta_corte' => $request->meta_corte,
            'valor_estimado_ganho' => $request->valor_estimado_ganho,
            'contas_corte' => $request->contas_corte,
        ]);

        //return response the consulting report created
        return response()->json([
            'message' => 'Consulting report created',
            'consulting_report' => $consultingReport
        ], 201);
    }

    public function show(ConsultingReport $consultingReport)
    {
        //return the consulting report corresponding to the id received
        return $consultingReport;
    }

    public function update(Request $request, ConsultingReport $consultingReport)
    {
        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => 'Você não tem permissão para acessar essa rota'
            ], 403);
        }

        //validate the request received
        $request->validate([
            'user_id' => 'exists:users,id',
            'consultant_id' => 'exists:consultants,id',
            'relatorio' => 'max:2555',
            'meta_corte' => 'numeric|min:0',
            'valor_estimado_ganho' => 'numeric|min:0',
            'contas_corte' => 'numeric|min:0',
        ]);

        //verify if the the request is valid, if not return a error message
        if (!$request) {
            return response()->json([
                'message' => 'Invalid request',
                'errors' => $request->errors()
            ], 400);
        }

        //if the request field are not empty, update the consulting report, if not, keep the old value
        $consultingReport->user_id = $request->user_id ? $request->user_id : $consultingReport->user_id;
        $consultingReport->consultant_id = $request->consultant_id ? $request->consultant_id : $consultingReport->consultant_id;
        $consultingReport->relatorio = $request->relatorio ? $request->relatorio : $consultingReport->relatorio;
        $consultingReport->meta_corte = $request->meta_corte ? $request->meta_corte : $consultingReport->meta_corte;
        $consultingReport->valor_estimado_ganho = $request->valor_estimado_ganho ? $request->valor_estimado_ganho : $consultingReport->valor_estimado_ganho;
        $consultingReport->contas_corte = $request->contas_corte ? $request->contas_corte : $consultingReport->contas_corte;

        $response = $consultingReport->save();

        //if the consulting report was updated, return response the consulting report updated
        if($response){
            return response()->json([
                'message' => 'Consulting report updated',
                'consulting_report' => $consultingReport
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConsultingReport $consultingReport)
    {
        //if user authenticated is not admin, return a error message
        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => 'Você não tem permissão para acessar essa rota'
            ], 403);
        }

        //delete the consulting report corresponding to the id received
        $response = $consultingReport->delete();

        //return response if success or error
        if($response){
            return response()->json([
                'message' => 'Consulting report deleted'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
