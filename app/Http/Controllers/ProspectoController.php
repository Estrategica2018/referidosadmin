<?php

namespace App\Http\Controllers;

use App\Estado;
use App\Mail\AdminMail;
use App\Trazabilidad;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Prospecto;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
class ProspectoController extends Controller
{

          public function  index (){
            $prospectos = Prospecto::with('user')->get();
            return view('prospectos');
          }
          public function prospectos_tabla(Request $request){
            $prospectos = Prospecto::with('user','estado')
                ->whereHas('user',function ($query){
                    $query->where('estado',true);
                })->get();
            return DataTables::of($prospectos)

                ->addindexColumn()
                ->make(true);
          }
          public function detalles_prospecto(Request $request,$id_prospecto){
            $prospecto = Prospecto::with('user','estado')->where('id_prospecto',$id_prospecto)->first();

            return view('detallesProspecto')
                ->with('id_prospecto',$id_prospecto)
                ->with('estado',$prospecto->estado->nombre);

          }
          public function estado_prospecto(Request $request ,$id_prospecto){
              $prospecto = Prospecto::with('estado','trazabilidad')
                  ->where('id_prospecto',$id_prospecto)->first();
              $response = array(
                  'status' => 'success',
                  'msg' => $prospecto,
              );
              return response()->json($response);
          }
          public function editar_prospecto(Request$request){
              $fecha = Carbon::createFromFormat('d/m/Y', $request->get('fecha'));
              $trazabilidad = new Trazabilidad;
              $trazabilidad->id_prospecto = $request->get('id_prospecto');
              $trazabilidad->id_estado = $request->get('estado');
              $trazabilidad->fecha = $fecha;
              $trazabilidad->hora = $request->get('hora');
              $trazabilidad->observacion = $request->get('observacion');
              $trazabilidad->save();
              Prospecto::where('id_prospecto',$request->get('id_prospecto'))
                  ->update(['id_estado'=>$request->get('estado')]);
              $estado = Estado::find($request->get('estado'));
              $contenido = "un mensaje de prueba";
              $prospeto = Prospecto::with('user')
                  ->where('id_prospecto',$request->get('id_prospecto'))
                  ->first();
              Mail::to($prospeto->user->correo)
               ->cc('operaciones@estrategicaco.com')
              ->send(new AdminMail($contenido,'Nontificaci��n App Referidos'));
              $response = array(
                  'status' => 'success',
                  'msg' => $estado->nombre,
              );
              return response()->json($response);

          }
         
}
