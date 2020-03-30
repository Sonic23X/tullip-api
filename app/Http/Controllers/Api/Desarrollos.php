<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use App\Entities\Desarrollo;
use App\Entities\Prototipo;
use App\Entities\Inmueble;
use App\User;

class Desarrollos extends Controller
{
    public function index(Request $request) {
        $desarrollos = Desarrollo::select('id', 'nombre')->take(10)->get();
        //Maximun 11 quick replies TODO: more button

        switch($request->input('block', '')){
            case 'prospecto':
                foreach ($desarrollos as $desarrollo) {
                    $quick_replies[] = [
                        "title" => $desarrollo->nombre,
                        "set_attributes" =>
                            [
                                "fraccionamiento_prospecto" => $desarrollo->nombre,
                                "fraccionamiento_id_prospecto" => $desarrollo->id
                            ],
                        "block_names" => ["Registro de Prospectos 1"],
                        "type" => "show_block",
                    ];
                }

                $response = [
                    "messages" => [
                        [
                            "text" =>  "¿En qué fraccionamiento deseas registrar a tu prospecto?",
                            "quick_replies" => $quick_replies
                        ]
                    ]
                ];
                break;
            case 'visita' :
                foreach ($desarrollos as $desarrollo) {
                    $quick_replies[] = [
                        "title" => $desarrollo->nombre,
                        "set_attributes" =>
                            [
                                "fraccionamiento" => $desarrollo->nombre,
                                "fraccionamiento_id" => $desarrollo->id
                            ]
                    ];
                }

                $response = [
                    "messages" => [
                        [
                            "text" =>  "¿En qué fraccionamiento nos visita?",
                            "quick_replies" => $quick_replies
                        ]
                    ]
                ];
                break;
            case 'disponibles' :
                foreach ($desarrollos as $desarrollo) {
                    $quick_replies[] = [
                        "title" => $desarrollo->nombre,
                        "set_attributes" =>
                            [
                                "fraccionamiento" => $desarrollo->nombre,
                                "fraccionamiento_id" => $desarrollo->id
                            ]
                    ];
                }

                $response = [
                    "messages" => [
                        [
                            "text" =>  "¿En qué fraccionamiento busca los lotes?",
                            "quick_replies" => $quick_replies
                        ]
                    ]
                ];
                break;
        }

        return json_encode($response);
    }


    public function prototipos(Request $request) {
        $prototipos = Prototipo::select('id','nombre')
                    ->where('id_desarrollo',$request->input('fraccionamiento_id'))
                    ->get();
        if(empty($prototipos)){
               $response = [
                "messages" => [
                    [
                        "text" =>  "No hay prototipos, intenta en otro fraccionamiento",
                        "redirect_to_blocks" => ["Casas disponibles"]
                    ]
                ]
            ];

            return json_encode($response);
        }

        foreach ($prototipos as $prototipo) {
            $quick_replies[] = [
                "title" => $prototipo->nombre,
                "set_attributes" =>
                [
                    "prototipo" => $prototipo->nombre,
                    "prototipo_id" => $prototipo->id
                ]
            ];
        }

        $response = [
            "messages" => [
                [
                    "text" =>  "¿Qué prototipo buscas?",
                    "quick_replies" => $quick_replies
                ]
            ]
        ];

        return json_encode($response);
    }

     public function lotes(Request $request) {
        $inmuebles = Inmueble::select('id','manzana','lote')
                    ->where([
                        ['id_prototipo',$request->input('prototipo_id')],
                        ['status','libre']
                    ])
                    ->get();
        $list_inmuebles ='';

        $count = count($inmuebles);

        foreach ($inmuebles as $inmueble) {

            $list_inmuebles .="-> Mz. {$inmueble->manzana} / Lt. {$inmueble->lote}
            ";
        }

        if($count == 0){
               $response = [
                "messages" => [
                    [
                        "text" =>  "No hay casas disponibles, intenta en otro fraccionamiento o prototipo",
                        "redirect_to_blocks" => ["Casas disponibles"]
                    ]
                ]
            ];

            return json_encode($response);
        }

        $response = [
            "messages" => [
                [
                    "text" =>  "{$count} Casas disponibles:
                    {$list_inmuebles}"
                ]
            ]
        ];

        return json_encode($response);
    }

    public function getDesarrollos($pageSize, $currentPage)
    {
      $desarollo = null;
      $totalItem = null;

      if ($currentPage == "1")
      {
        $desarollo = Desarrollo::limit($pageSize)
                     ->get();

        $totalItem = Desarrollo::limit($pageSize)
                         ->count();
      }
      else
      {
        $users = Desarrollo::offset($pageSize * ($currentPage - 1))
                     ->limit($pageSize)
                     ->get();

        $totalItem = Desarrollo::offset($pageSize * ($currentPage - 1))
                     ->limit($pageSize)
                     ->count();
      }

      $response = array
      (
         'status' => true,
         'totalItem' => $totalItem,
         'totalPage' => round(Desarrollo::count() / $pageSize),
         'pageSize' => $pageSize,
         'currentPage' => $currentPage,
         'data' => $desarollo
      );


      return $response;
    }

}
