<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    private $userId;

    /*function handle( Request $request )
    {
        Log::info("========\nBotmanController - received POST", $request->all());

        $config =  
        [ ];

        DriverManager::loadDriver( \BotMan\Drivers\Web\WebDriver::class );

        $botman = BotManFactory::create( $config, null );

        $this->userId = $request[ 'usuario' ];

        $botman->hears( 'hola', function ( BotMan $bot ) 
        {   
            $user = User::find( $this->userId );

            $bot->reply( 'Que tal ' . $user->name . '. ¿En que te puedo ayudar?' );
        });

        $botman->hears( 'test', function ( BotMan $bot ) 
        {
            $bot->reply( 'prueba de postman' );
        });

        $botman->fallback( function ( $bot ) 
        {
            $bot->reply( 'No entendí lo que escribio, ¿Podria intentarlo de otra forma?');
        });

        $botman->listen( );
    }*/

    public function test2(Request $request)
	{
	    \Log::info($request->all());
        $tipo_credito = $request->input("tipo_credito_infonavit_calculadora");
        $mmc = $request->input("monto_maximo_credito_calculadora");
        $ssv = $request->input("ssv_calculadora");
        $fraccionamiento = $request->input("fraccionamiento_calculadora");
        $ecotecnologias = $request->input("ecotecnologias");
        $valor_casa = $request->input("valor_casa_calculadora");
        $valor_avaluo = $request->input("valor_avaluo_casa_calculadora");
        $nombre = $request->input("nombre_preca_infonavit");
        if ($fraccionamiento == "Fracc La Luz") {
            if ($valor_avaluo > 700000) {
                $avaluo = 3500;
                $imp_y_der = (($valor_casa / 100) * 2) + 5500 + 37204;
            }
            else {
                $avaluo = 3500;
                $imp_y_der = (($valor_casa / 100) * 2) + 5500 + 500;
            }
        }	
        elseif ($fraccionamiento == "Fracc El Recinto") {
            $avaluo = 974.40;
            $imp_y_der = (($valor_casa / 100) * 4.5);
        }
                    
        if ($tipo_credito == "2do Credito") {
            if ($mmc>$valor_casa) {
                $gtof = ($valor_casa / 100) * 5;
                $gastos = $gtof + $imp_y_der + $ecotecnologias + $avaluo;
            }
            else {
                $gtof = ($mmc / 100) * 5;
                $gastos = $gtof + $imp_y_der + $ecotecnologias + $avaluo;
            }
            if ($mmc > $valor_casa && $ssv>$gastos) {
                $diferencia = $ssv - $gastos;
                $credito = $valor_casa - $diferencia;
                $diferencia_a_cubrir = 0;
            }
            elseif ($mmc > $valor_casa && $ssv < $gastos) {
                $diferencia = $gastos - $ssv;
                $credito = $valor_casa;
                $diferencia_a_cubrir = $diferencia;
            }
            elseif ($mmc < $valor_casa && $ssv > $gastos) {
                $diferencia = $ssv - $gastos;
                $diferencia_credito = $valor_casa - $mmc;
                $diferencia_mmc_precio = $diferencia_credito - $diferencia;
                $credito = $mmc;
                $diferencia_a_cubrir = $diferencia_mmc_precio;
            }
            elseif ($mmc < $valor_casa && $ssv < $gastos) {
                $diferencia = $gastos - $ssv;
                $credito = $mmc;
                $diferencia_a_cubrir = $diferencia + ($valor_casa - $mmc);
            }
        }
        else {
            if ($mmc>$valor_casa) {
                $gtof = ($valor_casa / 100) * 3;
                $gastos = $gtof + $imp_y_der + $ecotecnologias + $avaluo;
            }
            else {
                $gtof = ($mmc / 100) * 3;
                $gastos = $gtof + $imp_y_der + $ecotecnologias + $avaluo;
            }
            if ($mmc > $valor_casa && $ssv>$gastos) {
                \Log::info('uno');
                $diferencia = $ssv - $gastos;
                $credito = $valor_casa - $diferencia;
                $diferencia_a_cubrir = 0;
            }
            elseif ($mmc > $valor_casa && $ssv < $gastos) {
                \Log::info('dos');
                $diferencia = $gastos - $ssv;
                $credito = $valor_casa;
                $diferencia_a_cubrir = $diferencia;
            }
            elseif ($mmc < $valor_casa && $ssv > $gastos) {
                \Log::info('tres');
                $diferencia = $ssv - $gastos;
                $diferencia_credito = $valor_casa - $mmc;
                $diferencia_mmc_precio = $diferencia_credito - $diferencia;
                $credito = $mmc;
                $diferencia_a_cubrir = $diferencia_mmc_precio;
            }
            elseif ($mmc < $valor_casa && $ssv < $gastos) {
                \Log::info('cuatro');
                $diferencia = $gastos - $ssv;
                $credito = $mmc;
                $diferencia_a_cubrir = $diferencia + ($valor_casa - $mmc);
            } 
            else { 
                \Log::info('ninguno ');
            }
        }
                
        $mmc = number_format($mmc, 2);
        $ssv = number_format($ssv, 2);
        $valor_casa = number_format($valor_casa, 2);
        $gastos = number_format($gastos, 2);
        $credito = number_format($credito, 2);
        $diferencia = number_format($diferencia, 2);
        $diferencia_a_cubrir = number_format($diferencia_a_cubrir, 2);

        $ecotecnologias = empty($ecotecnologias) ? 0 : number_format($ecotecnologias, 2);
					
		$response = [
            "set_attributes" => [
                "tipo_credito_infonavit_calculadora" => "$tipo_credito",
                "monto_maximo_credito_calculadora" => "$mmc",
                "ssv_calculadora" => "$ssv",
                "valor_casa_calculadora" => "$valor_casa",
                "gastos_calculadora" => "$gastos",
                "credito_calculadora" => "$credito",
                "diferencia_calculadora" => "$diferencia",
                "diferencia_a_cubrir" => "$diferencia_a_cubrir",
                "fraccionamiento_calculadora" => "$fraccionamiento",
                "nombre_preca_infonavit" =>"$nombre",
                "ecotecnologias" =>"$ecotecnologias"
            ],
            "messages" => [
                [
                    "text" => "A continuación te presento los resultados para la precalificación de $nombre"
                ],
                [
                    "text" => "El valor de la casa es de $ $valor_casa en el fraccionamiento $fraccionamiento a nombre de $nombre "
                ],
                [
                    "text" => "El Monto de Crédito inicial por esta casa será de: $ $credito"
                ],
                [
                    "text" => "La suma de los gastos (inclue GTOF, Impuestos y Derechos, Avalúo y Ecotecnologías - solo en La Luz) será de :$ $gastos"
                ],
                [
                    "text" => "El Saldo de Subcuenta de Vivienda es de: $ $ssv"
                ],
                [
                    "text" => "La diferencia a cubrir por parte del cliente es de: $ $diferencia_a_cubrir"
                ],
			]		
		];
		
		return response()->json($response);
	}

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function test3(Request $request)
	{
		$fraccionamiento_fovissste = $request->input("fraccionamiento_calculadora");
		$mmc = $request->input("mmc_fovissste");
		$precio_casa = $request->input("precio_casa_fovissste");
		$nombre = $request->input("nombre_cliente_fovissste");
		$avaluo = 3000 * 1.16;
		$seguro_calidad = ($mmc * 0.0065) * 1.16;
		$escritura = ($mmc / 100) * 7;
		if ($mmc > $precio_casa) {
            $gastos_fovissste = $avaluo + $seguro_calidad + $escritura;
            $diferencia_fovissste = 0;
        }
		else {
            $gastos_fovissste = $avaluo + $seguro_calidad + $escritura;
            $mmc_mas_gastos = $mmc - $gastos_fovissste;
            $diferencia_fovissste = $precio_casa - $mmc_mas_gastos;
        }
			
        $mmc = number_format($mmc, 2);
        $precio_casa = number_format($precio_casa, 2);
        $avaluo = number_format($avaluo, 2);
        $seguro_calidad = number_format($seguro_calidad, 2);
        $escritura = number_format($escritura, 2);
        $gastos_fovissste = number_format($gastos_fovissste, 2);
        $diferencia_fovissste = number_format($diferencia_fovissste, 2);
			
		$response = [
			"set_attributes" => [
                "fraccionamiento_calculadora" => "$fraccionamiento_fovissste",
                "mmc_fovisste" => "$mmc",
                "diferencia_a_cubrir_fovissste" => "$diferencia_fovissste",
                "precio_casa_fovissste" => "$precio_casa",
                "nombre_cliente_fovissste" => "$nombre",
                "gastos_calculadora_fovissste" => "$gastos_fovissste"
            ],
            "messages" => [
                [
                    "text" => "Estos son los resultados de la calculadora de Fovissste de $nombre para una casa de $ $precio_casa para el fraccionamiento $fraccionamiento_fovissste"
                ],
                [
                    "text" => "El Monto de Crédito inicial por esta casa será de: $ $mmc"
                ],
                [
                    "text" => "La suma de los gastos será de :$ $gastos_fovissste"
                ],
                [
                    "text" => "La diferencia a cubrir por parte del cliente es de: $ $diferencia_fovissste"
                ]
			]		
		];
		return response()->json($response);
	}

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function test6(Request $request)
	{
        $tipo_credito = $request->input("tipo_credito_infonavit_calculadora");
        $mmc = $request->input("monto_maximo_credito_calculadora");
        $ssv = $request->input("ssv_calculadora");
        $fraccionamiento = $request->input("fraccionamiento_calculadora");
        $ecotecnologias = $request->input("ecotecnologias");
        $valor_casa = $request->input("valor_casa_calculadora");
        $nombre = $request->input("nombre_preca_infonavit");
        $imp_y_der = ($valor_casa / 100) * 4.5;
        $avaluo = 3500;
		if ($tipo_credito == "2do Credito") {
			if ($mmc>$valor_casa) {
				$gtof = ($valor_casa / 100) * 5;
				$gastos = $gtof + $imp_y_der + $ecotecnologias + $avaluo;
			}
			else {
				$gtof = ($mmc/100)*5;
				$gastos = $gtof + $imp_y_der + $ecotecnologias + $avaluo;
			}
			if ($mmc > $valor_casa && $ssv>$gastos) {
				$diferencia = $ssv - $gastos;
				$credito = $valor_casa - $diferencia;
				$diferencia_a_cubrir = 0;
			}
			elseif ($mmc > $valor_casa && $ssv < $gastos) {
				$diferencia = $gastos - $ssv;
				$credito = $valor_casa;
				$diferencia_a_cubrir = $diferencia;
			}
			elseif ($mmc < $valor_casa && $ssv > $gastos) {
				$diferencia = $ssv - $gastos;
				$diferencia_credito = $valor_casa - $mmc;
				$diferencia_mmc_precio = $diferencia_credito - $diferencia;
				$credito = $mmc;
				$diferencia_a_cubrir = $diferencia_mmc_precio;
			}
			elseif ($mmc < $valor_casa && $ssv < $gastos) {
				$diferencia = $gastos - $ssv;
				$credito = $mmc;
				$diferencia_a_cubrir = $diferencia + ($valor_casa - $mmc);
			}
		}
		else {
			if ($mmc>$valor_casa) {
				$gtof = ($valor_casa / 100) * 3;
				$gastos = $gtof + $imp_y_der + $ecotecnologias + $avaluo;
			}
			else {
				$gtof = ($mmc / 100) * 3;
				$gastos = $gtof + $imp_y_der + $ecotecnologias + $avaluo;
			}
			if ($mmc > $valor_casa && $ssv > $gastos) {
				$diferencia = $ssv - $gastos;
				$credito = $valor_casa - $diferencia;
				$diferencia_a_cubrir = 0;
			}
			elseif ($mmc > $valor_casa && $ssv < $gastos) {
				$diferencia = $gastos - $ssv;
				$credito = $valor_casa;
				$diferencia_a_cubrir = $diferencia;
			}
			elseif ($mmc < $valor_casa && $ssv > $gastos) {
				$diferencia = $ssv - $gastos;
				$diferencia_credito = $valor_casa - $mmc;
				$diferencia_mmc_precio = $diferencia_credito - $diferencia;
				$credito = $mmc;
				$diferencia_a_cubrir = $diferencia_mmc_precio;
			}
			elseif ($mmc < $valor_casa && $ssv < $gastos) {
				$diferencia = $gastos - $ssv;
				$credito = $mmc;
				$diferencia_a_cubrir = $diferencia + ($valor_casa - $mmc);
			}
		}
					
        $mmc = number_format($mmc,2);
        $ssv = number_format($ssv,2);
        $valor_casa = number_format($valor_casa,2);
        $gastos = number_format($gastos,2);
        $credito = number_format($credito,2);
        $ecotecnologias = number_format($ecotecnologias,2);
        $diferencia = number_format($diferencia,2);
        $diferencia_a_cubrir = number_format($diferencia_a_cubrir,2);
					
		$response = [
            "set_attributes" => [
                "tipo_credito_infonavit_calculadora" => "$tipo_credito",
                "monto_maximo_credito_calculadora" => "$mmc",
                "ssv_calculadora" => "$ssv",
                "valor_casa_calculadora" => "$valor_casa",
                "gastos_calculadora" => "$gastos",
                "credito_calculadora" => "$credito",
                "ecotecnologias"=> "$ecotecnologias",
                "diferencia_calculadora" => "$diferencia",
                "diferencia_a_cubrir" => "$diferencia_a_cubrir",
                "fraccionamiento_calculadora" => "$fraccionamiento",
                "nombre_preca_infonavit" =>"$nombre"
            ],
            "messages" => [
                [
                    "text" => "A continuación te presento los resultados para la precalificación de $nombre"
                ],
                [
                    "text" => "El valor de la casa es de $ $valor_casa en el fraccionamiento $fraccionamiento a nombre de $nombre "
                ],
                [
                    "text" => "El Monto de Crédito inicial por esta casa será de: $ $credito"
                ],
                [
                    "text" => "La suma de los gastos será de :$ $gastos"
                ],
                [
                    "text" => "El Saldo de Subcuenta de Vivienda es de: $ $ssv"
                ],
				[
                    "text" => "El monto del cheque por Ecotecnologías que dará el Infonavit es de: $ $ecotecnologias"
                ],
                [
                    "text" => "La diferencia a cubrir por parte del cliente es de: $ $diferencia_a_cubrir"
                ],
			]		
		];
		
		return response()->json($response);
	}

    /* Vendedor end-points */
    public function searchSeller(Request $request)
    {
        if (!empty($request->input('email_vendedor', null))) {
            $vendedor = User::where('email', $request->input('email_vendedor'))->first();
        } 
        else {
            $response = [
                "messages" => [
                    [
                        "text" =>  "A ocurrido un error, intende más tarde."
                    ]
                ],
                "set_attributes"=> [
                    "login" => false,
                ],
            ];
            return json_encode($response);
        }

        try {
            $response = [
                "set_attributes"=> [
                    "nombre" => "{$vendedor->name}",
                    "email" => "{$vendedor->email}",
                    "login" => true,
                ],
                "messages" => [
                    [
                        "text" =>  "Hola {$vendedor->name}, ¡bienvenido a Tullip! "
                    ]
                ],
                "redirect_to_blocks" => [ "menu" ]
            ];
        } 
        catch (\Exception $e) {
            $response = [
                "messages" => [
                    [
                        "text" =>  "Vendedor no registrado."
                    ]
                ],
                "set_attributes"=> [
                    "login" => false,
                ],
            ];
        }
        
        return json_encode($response);

    }

    public function newProspect(Request $request) 
    {
        try {
            if (!empty($request->input('email', null))) {
                $vendedor = User::where('email', $request->input('email'))->first();
            } 
            else {
                $response = [
                    "messages" => [
                        [
                            "text" =>  "Por alguna razón no se envió el vendedor, vuelve a intentarlo."
                        ]
                    ]
                ];

                return json_encode($response);
            }

            if (!empty($request->input('fraccionamiento_id_prospecto', null))) {
                $desarrollo = Desarrollo::where('id', $request->input('fraccionamiento_id_prospecto'))->first();
            } 
            else {
                $response = [
                    "messages" => [
                        [
                            "text" =>  "Por alguna razón no se seleccionó el fraccionamiento del prospecto, vuelve a intentarlo."
                        ]
                    ]
                ];

                return json_encode($response);
            }
            
            $prospecto = Cliente::where('anexo_detalles->email', $request->input('email_prospecto'))->first();
            
            if (empty($prospecto))
                $prospecto = Cliente::where('anexo_detalles->telefono_celular', $request->input('celular_prospecto'))->first();

            if (empty($prospecto)) {
                $prospecto = new Cliente;

                $data = [
                    'nombre' => $request->input('nombre_prospecto', ''),
                    'apellido_paterno' => $request->input('apellido_paterno_prospecto',''),
                    'apellido_materno' => $request->input('apellido_materno_prospecto',''),
                    'telefono_celular' => $request->input('celular_prospecto', ''),
                    'email' => $request->input('email_prospecto', ''),
                    'como_se_entero' => $request->input('contacto_prospecto', ''),
                    'interes' => $request->input('interes_cierre_prospecto', ''),
                    'portal' => $request->input('portal_prospecto', ''),
                    'promocion' => $request->input('promocion_prospecto', ''),
                    'telefono_casa' => '',
                ];

                $credito_data = [];
                $tipo = $request->input('tipo_credito_prospecto', '');
                switch ($tipo) {
                    case 'Infonavit':
                        $credito = 'infonavit';
                        $tipo = 'Infonavit Tradicional';
                        break;

                    case 'Cofinavit':
                        $credito = 'cofinavit';
                        $tipo = 'Cofinavit';
                        break;

                    case 'Fovissste':
                        $credito = 'fovissste';
                        $tipo = 'Fovissste Tradicional';
                        break;

                    case 'Alia2':
                        $credito = 'alia2';
                        $tipo = 'Alia2';
                        break;

                    case 'Hipotecario':
                        $credito = 'bancario';
                        $tipo = 'Bancarios';
                        break;
                    
                    case 'Isssfam':
                    case 'Caprepol':
                    default:
                        $credito = 'otro';
                        break;
                }
                
                $credito_data[$credito] = [
                    [
                        'tipo_credito' => $tipo
                    ],
                ];      				

                $prospecto->nombre = $data['nombre'].' '.$data['apellido_paterno'].' '.$data['apellido_materno'];
                $prospecto->anexo_detalles = $data;
                $prospecto->anexo_credito = $credito_data;
                $prospecto->id_usuario = $vendedor->id;
                $prospecto->id_desarrollo = $desarrollo->id;
                $prospecto->hash = str_random(10);
                $prospecto->completado = 0;
                $prospecto->referencia_bancaria = '';
                $prospecto->condicion_changed = \Carbon\Carbon::now();
                $prospecto->save();

                if ($prospecto->id) {
                    $new_tracking = new Seguimiento;
                    $new_tracking->tipo 			= 'nuevo';
                    $new_tracking->mensaje 			= '';
                    $new_tracking->id_usuario = $vendedor->id;
                    $new_tracking->fecha = \Carbon\Carbon::now();
                    $new_tracking->id_cliente = $prospecto->id;
                    $new_tracking->save();

                    $prospecto->setCompletedPercent();
                }
            }  
            else {
                $response = [
                    "messages" => [
                        [ "text" =>  "Prospecto ya fue registrado previamente" ]
                    ]
                ];

                return json_encode($response);
            }          
        } 
        catch (\Exception $e) {
            $response = [
                "messages" => [
                    [
                        "text" =>  "Hubo un error al tratar de guardar el prospecto. Error: ".$e->getMessage()
                    ]
                ]
            ];

            return json_encode($response);
        }
        
        $response = [
            "messages" => [
                [
                    "text" =>  "Hemos registrado exitosamente a tu prospecto.  Los datos son:
                                Nombre:
                                {$prospecto->nombre}
                                Celular:
                                {$prospecto->anexo_detalles->telefono_celular}
                                Email:
                                {$prospecto->anexo_detalles->email}
                                Fraccionamiento de interés:
                                {$prospecto->desarrollo->nombre}
                                Nivel de Interés:
                                {$prospecto->anexo_detalles->interes}",
                ]
            ]
        ];

        return json_encode($response);
    }

    /* Desarrollo end-points */
    public function desarrollos(Request $request) 
    {
        //Buscamos desarrollos por usuario

        $desarrollos = Desarrollo::select('id', 'nombre')->take(10)->get(); 
        
        switch ($request->input('block', '')) {
            case 'prospecto':
                foreach ($desarrollos as $desarrollo) {
                    $quick_replies[] = [
                        "title" => $desarrollo->nombre,
                        "set_attributes" => [
                            "fraccionamiento_prospecto" => $desarrollo->nombre,
                            "fraccionamiento_id_prospecto" => $desarrollo->id
                        ],
                        "block_names" => [ "Registro de Prospectos 1" ],
                        "type" => "show_block",
                    ];
                }

                $response = [
                    "messages" => [
                        [
                            "text" =>  "¿En qué proyecto desea registrar a tu prospecto?",
                            "quick_replies" => $quick_replies
                        ]
                    ]
                ];
                break;
            case 'visita': 
                foreach ($desarrollos as $desarrollo) {
                    $quick_replies[] = [
                        "title" => $desarrollo->nombre,
                        "set_attributes" => [
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
            case 'disponibles': 
                foreach ($desarrollos as $desarrollo) {
                    $quick_replies[] = [
                        "title" => $desarrollo->nombre,
                        "set_attributes" => [
                            "fraccionamiento" => $desarrollo->nombre,
                            "fraccionamiento_id" => $desarrollo->id
                        ]
                    ];
                }

                $response = [
                    "messages" => [
                        [
                            "text" =>  "¿En qué proyecto busca los lotes?",
                            "quick_replies" => $quick_replies
                        ]
                    ]
                ];
                break;
        }

        return json_encode($response);
    }

    /* Seguimientos end-point */
    public function saveSeguimiento(Request $request) 
    {
        try {
            if (!empty($request->input('email', null)))
                $vendedor = User::where('email', $request->input('email'))->first();
            else {
                $response = [
                    "messages" => [
                        [
                            "text" =>  "Error al seleccionar al vendedor, intente más tarde."
                        ]
                    ]
                ];

                return json_encode($response);
            }

            if (!empty($request->input('fraccionamiento_id', null)))
                $desarrollo = Desarrollo::where('id', $request->input('fraccionamiento_id'))->first();
            else {
                $response = [
                    "messages" => [
                        [
                            "text" =>  "Por alguna razón no se seleccionó el fraccionamiento de la visita, vuelve a intentarlo."
                        ]
                    ]
                ];

                return json_encode($response);
            }
            
            $prospecto = Cliente::where('anexo_detalles->email', $request->input('email_visitante'))->first();
            if (empty($prospecto)) {
                $prospecto = Cliente::where('anexo_detalles->telefono_celular', $request->input('celular_visitante'))->first();
                if (empty($prospecto)) {
                    $prospecto = new Cliente;
                
                    $data = [
                        'nombre' => $request->input('nombre_visitante', ''),
                        'apellido_paterno' => $request->input('apellido_paterno_visitante', ''),
                        'apellido_materno' => $request->input('apellido_materno_visitante', ''),
                        'telefono_celular' => $request->input('celular_visitante', ''),
                        'email' => $request->input('email_visitante', ''),
                        'como_se_entero' => $request->input('medio_contacto_visitante', ''),
                    ];
                    
                    $credito_data = [];
                    $tipo = $request->input('tipo_credito_visitante', '');
                    switch ($tipo) {
                        case 'Infonavit':
                            $credito = 'infonavit';
                            $tipo = 'Infonavit Tradicional';
                            break;

                        case 'Cofinavit':
                            $credito = 'cofinavit';
                            $tipo = 'Cofinavit';
                            break;

                        case 'Fovissste':
                            $credito = 'fovissste';
                            $tipo = 'Fovissste Tradicional';
                            break;

                        case 'Alia2':
                            $credito = 'alia2';
                            $tipo = 'Alia2';
                            break;

                        case 'Hipotecario':
                            $credito = 'bancario';
                            $tipo = 'Bancarios';
                            break;
                        
                        case 'Isssfam':
                        case 'Caprepol':
                        default:
                            $credito = 'otro';
                            break;
                    }
                    
                    $credito_data[$credito] = [
                        [
                            'tipo_credito' => $tipo
                        ],
                    ];      

                    $prospecto->nombre = $data['nombre'].' '.$data['apellido_paterno'].' '.$data['apellido_materno'];
                    $prospecto->anexo_detalles = $data;
                    $prospecto->anexo_credito = $credito_data;
                    $prospecto->id_usuario = $vendedor ? $vendedor->id : null;
                    $prospecto->id_desarrollo = $desarrollo->id;
                    $prospecto->id_empresa = $vendedor ? $vendedor->id_empresa : 0;
                    $prospecto->hash = str_random(10);
                    $prospecto->completado = 0;
                    $prospecto->referencia_bancaria = '';
                    $prospecto->condicion_changed = \Carbon\Carbon::now();
                    $prospecto->save();
                }
            }
           
            if ($prospecto->id) {
                $new_tracking = new Seguimiento;
                $new_tracking->tipo = $request->input('tipo');
                $new_tracking->mensaje = $prospecto->nombre . ' visitó el fraccionamiento ' . $desarrollo->nombre;
                $new_tracking->id_usuario = $prospecto->id_usuario;
                $new_tracking->id_empresa = $vendedor ? $vendedor->id_empresa : 0;
                $new_tracking->fecha = \Carbon\Carbon::now();
                
                if ($request->input('visita_anterior') == 'SI')
                    $new_tracking->primera_visita = 1;    
                
                $new_tracking->id_cliente = $prospecto->id;
                $new_tracking->save();
                $prospecto->setCompletedPercent();
            }
        } 
        catch (\Exception $e) {
            $response = [
                "messages" => [
                    [
                        "text" =>  "Hubo un error al tratar de guardar la visita. Error: ".$e->getMessage()
                    ]
                ]
            ];

            return json_encode($response);
        }

        try {
            $response = [
                "messages" => [
                    [
                        "text" =>  
                            "{$prospecto->nombre} nos visitó en el fraccionamiento {$desarrollo->nombre}, registrando los siguientes datos:
                            Celular: {$prospecto->anexo_detalles->telefono_celular}
                            Correo Electrónico: {$prospecto->anexo_detalles->email}
                            Y su primer medio de contacto fue {$prospecto->anexo_detalles->como_se_entero}"
                    ]
                ]
            ];
        } 
        catch (\Exception $e) {
            $response = [
                "messages" => [
                    [
                        "text" =>  "Visita registrada."
                    ]
                ]
            ];
        }
        
        return json_encode($response);
    }
}
