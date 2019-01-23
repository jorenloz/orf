<?php
/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

OrfeoGpl- ArgoGpl  Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
#require '/var/www/html/orfeocore/config.php';

class Usuario {
    /*** Attributes:
     * Clase que maneja los usuarios
     *
     * $dirMail        array Variable que almacena el correo electronico del tercero de un radicado.
     * $dirNombre      array Almacena los nombres de terceros relacionados con un radicado.
     * $dirNomRemDes   array Almacena los nombres completos de los terceros de un radicado.
     */

    var $db;
    var $result;
    var $sgdOemCodigo;
    var $sgdCiuCodigo;
    var $dirMail;  //  Array, Variable que almacena el correo electronico del tercero de un radicado.

    function __construct($db){
        $this->db = $db;
		#$echo "<pre>";
    //$this->db->conn->debug=true;
		# $midriver=$this->db->driver;
    }

/** getInfoUsuario
  * Busca informacion de un usuario del sistema dependiendo del tipo de busqueda, que puede ser correo o documento o login.
    */
function getInfoUsuario($variableUsuario,$key,$tipoBusqueda="correo"){

  global $ruta_raiz,$keyWS;

  //Validamos que la key recibida sea valida
  if ($key == $keyWS)
  {
    $db = new ConnectionHandler($ruta_raiz);
    $upperMail=strtoupper($variableUsuario);
    $lowerMail=strtolower($variableUsuario);
    
    if(trim(strtolower($tipoBusqueda))=="correo"){
      $whereTipoBusqueda = "USUA_EMAIL='{$variableUsuario}' OR USUA_EMAIL='{$upperMail}' OR USUA_EMAIL='{$lowerMail}'";  
      
    }elseif(trim(strtolower($tipoBusqueda))=="documento"){
         $whereTipoBusqueda = "USUA_DOC='{$variableUsuario}' ";    
    }elseif(trim(strtolower($tipoBusqueda))=="login"){
        
        $whereTipoBusqueda = "USUA_LOGIN='{$variableUsuario}' ";    
   }
    

        $sql="SELECT USUA_LOGIN,USUA_DOC,DEPE_CODI,CODI_NIVEL,USUA_CODI,USUA_NOMB,USUA_EMAIL FROM USUARIO
                        WHERE  $whereTipoBusqueda ";
        $rs=$db->query($sql);
        
        if($rs && !$rs->EOF){
            $salida['usua_login'] = $rs->fields["USUA_LOGIN"];
            $salida['usua_doc']   = $rs->fields["USUA_DOC"];
            $salida['usua_depe']  = $rs->fields["DEPE_CODI"];
            $salida['usua_nivel'] = $rs->fields["CODI_NIVEL"];
            $salida['usua_codi']  = $rs->fields["USUA_CODI"];
            $salida['usua_nomb']  = $rs->fields["USUA_NOMB"];
            $salida['usua_email'] = $rs->fields["USUA_EMAIL"];
        }else{
          throw new Exception("El usuario $variableUsuario no existe $sql");

   }
     return $salida;
   }
  else
  {
    throw new Exception("Error, llave de consulta no valida");
  }

} 
    /** getInfoTerceroDir
  * Busca informacion de un ciudadano segun un identificador "id" en la tabla sgd_dir_drecciones .
  * 0 Lo busca en sgd_cui_ciudadadno
  * 2 Lo busca en sgd_oem_oempresas
  * 6 En la tabla "usuarios" del sistema
  * @var $tipoBusqueda  Que tipo de busqueda realizara "correo" Busca por el correo electrónico "codigo" Busca por el identificador en la tabla
  * @var $idDirDrecciones  Si trae un valor busca el tercero que se identifica con el id de la tabla sgd_dir_Direccioens
  */
function getInfoTerceroDir($idDirDrecciones){

  global $ruta_raiz,$keyWS;

  //Validamos que la key recibida sea valida
  echo $idDirDrecciones;
  if($idDirDrecciones){
   $iSql = "SELECT SGD_CIU_CODIGO, SGD_OEM_CODIGO, SGD_DOC_FUN
              FROM SGD_DIR_DRECCIONES
            WHERE id=$idDirDrecciones"; 
   $rs=$this->db->conn->query($iSql);
   if(!$rs->EOF){
       if($rs->fields["SGD_CIU_CODIGO"] && $rs->fields["SGD_CIU_CODIGO"]!=0) {
         $ciuCodigo = $rs->fields["SGD_CIU_CODIGO"];
         $whereTipoBusqueda0 = "SGD_CIU_CODIGO = '{$ciuCodigo}'";  
         $tipoTercero = 0;
       }elseif($rs->fields["SGD_OEM_CODIGO"] && $rs->fields["SGD_OEM_CODIGO"]!=0) {
         $oemCodigo = $rs->fields["SGD_OEM_CODIGO"];
         $whereTipoBusqueda0 = "SGD_OEM_CODIGO = '{$oemCodigo}'";  
         $tipoTercero=2;
       }elseif($rs->fields["SGD_FUN_CODIGO"] && $rs->fields["SGD_FUN_CODIGO"]!=0) {
         $funCodigo = $rs->fields["SGD_DOC_FUN"];
         $whereTipoBusqueda0 = "SGD_DOC_FUN = '{$docCodigo}'";  
         $tipoTercero=6;
       }else{
         $whereTipoBusqueda0 = "id = '{$idDirDrecciones}'";  
         $tipoTercero=5; 
      }
   
   }  
  }  
  
  if ($key == $keyWS)
  {
    $db = new ConnectionHandler($ruta_raiz);
    $upperMail=strtoupper($variableUsuario);
    $lowerMail=strtolower($variableUsuario);
    
  

  switch ($tipoTercero) {
  case 0:
   $iSql = "SELECT '' USUA_LOGIN,SGD_CIU_CEDULA DOCUMENTO,'' DEPE_CODI,'' CODI_NIVEL,'' USUA_CODI,SGD_CIU_NOMBRE NOMBRE, SGD_CIU_APELL1 APELLIDO1, SGD_CIU_APELL2 APELLIDO2,SGD_CIU_EMAIL EMAIL
               , SGD_CIU_DIRECCION DIRECCION, SGD_CIU_TELEFONO TELEFONO, SGD_CIU_CODIGO, DPTO_CODI, MUNI_CODI, ID_CONT, ID_PAIS
            FROM SGD_CIU_CIUDADANO WHERE $whereTipoBusqueda0";
   break;
  case 2:
   $iSql = "SELECT '' USUA_LOGIN,SGD_OEM_NIT DOCUMENTO,'' DEPE_CODI,'' CODI_NIVEL,'' USUA_CODI,SGD_OEM_OEMPRESA NOMBRE, SGD_OEM_REP_LEGAL APELLIDO1, SGD_OEM_SIGLA APELLIDO2, SGD_OEM_EMAIL EMAIL
               , SGD_OEM_DIRECCION DIRECCION, SGD_OEM_TELEFONO TELEFONO, SGD_OEM_CODIGO, DPTO_CODI, MUNI_CODI, ID_CONT, ID_PAIS
            FROM SGD_OEM_OEMPRESAS WHERE $whereTipoBusqueda0"; 
   break;
  case 6:
   $iSql = "SELECT USUA_LOGIN,USUA_DOC DOCUMENTO,DEPE_CODI,CODI_NIVEL,USUA_CODI,USUA_NOMB NOMBRE,USUA_EMAIL EMAIL 
               , USUA_DOC codigo
            FROM USUARIO WHERE $whereTipoBusqueda0"; 
            
  case 5:
   $iSql = "SELECT '' USUA_LOGIN,SGD_DIR_DOC DOCUMENTO,'' DEPE_CODI,'' CODI_NIVEL,'' USUA_CODI,SGD_DIR_NOMBRE NOMBRE,SGD_DIR_MAIL EMAIL
               , SGD_DIR_DIRECCION DIRECCION, SGD_DIR_TELEFONO TELEFONO, CIUDAD_ID, DEPARTAMENTO_ID, DPTO_CODI, MUNI_CODI, ID_CONT, ID_PAIS
            FROM SGD_DIR_DRECCIONES WHERE $whereTipoBusqueda0";             
   break;
  }
    $rs=$this->db->query($iSql);
    if($rs && !$rs->EOF){
      $salida['login'     ] = $rs->fields["USUA_LOGIN"];
      $salida['documento' ] = $rs->fields["DOCUMENTO" ];
      $salida['depe_codi' ] = $rs->fields["DEPE_CODI" ];
      $salida['usua_nivel'] = $rs->fields["CODI_NIVEL"];
      $salida['usua_codi' ] = $rs->fields["USUA_CODI" ];
      $salida['nombre'    ] = $rs->fields["NOMBRE"    ];
      $salida['apellido1' ] = $rs->fields["APELLIDO1" ];
      $salida['apellido2' ] = $rs->fields["APELLIDO2" ];
      $salida['direccion' ] = $rs->fields["DIRECCION" ];
      $salida['telefono'  ] = $rs->fields["TELEFONO"  ];
      $salida['codigo'    ] = $rs->fields["CODIGO"    ];
      $salida['email'     ] = $rs->fields["EMAIL"     ];
      $salida['dptoCodi'  ] = $rs->fields["DPTO_CODI" ];
      $salida['muniCodi'  ] = $rs->fields["MUNI_CODI" ];
      $salida['idPais'    ] = $rs->fields["ID_PAIS"   ];
      $salida['idCont'    ] = $rs->fields["ID_CONT"   ];
      
    }else{
     return false;
   }
       return $salida;
    }
    else
    {
      throw new Exception("Error, llave de consulta no valida");
    }

} 


    /** getInfoTercero
  * Busca informacion de un ciudadano en el sistema dependiendo del tipo de tercero.
  * 0 Lo busca en sgd_cui_ciudadadno
  * 2 Lo busca en sgd_oem_oempresas
  * 6 En la tabla "usuarios" del sistema
  * @var $tipoBusqueda  Que tipo de busqueda realizara "correo" Busca por el correo electrónico "codigo" Busca por el identificador en la tabla
  * @var $idDirDrecciones  Si trae un valor busca el tercero que se identifica con el id de la tabla sgd_dir_Direccioens
  */
function getInfoTercero($variableUsuario,$key,$tipoBusqueda="correo",$tipoTercero=0){

  global $ruta_raiz,$keyWS;

  //Validamos que la key recibida sea valida
  
  if ($key == $keyWS)
  {
    $db = new ConnectionHandler($ruta_raiz);
    $upperMail=strtoupper($variableUsuario);
    $lowerMail=strtolower($variableUsuario);
    
    if(trim(strtolower($tipoBusqueda))=="correo"){
      $whereTipoBusqueda0 = "SGD_CIU_EMAIL ILIKE '{$variableUsuario}'";  
      $whereTipoBusqueda6 = "USUA_EMAIL    ILIKE '{$variableUsuario}'";  
      $whereTipoBusqueda2 = "SGD_OEM_EMAIL ILIKE '{$variableUsuario}' ";  
    }elseif(trim(strtolower($tipoBusqueda))=="documento"){
      $whereTipoBusqueda0 = "SGD_CIU_CEDULA='{$variableUsuario}' ";    
      $whereTipoBusqueda6 = "USUA_DOC      ='{$variableUsuario}' ";    
      $whereTipoBusqueda2 = "SGD_OEM_NIT   ='{$variableUsuario}' ";    
    }elseif(trim(strtolower($tipoBusqueda))=="login"){
      $whereTipoBusqueda6 = "USUA_LOGIN    ='{$variableUsuario}' ";    
   }

  switch ($tipoTercero) {
  case 0:
   $iSql = "SELECT '' USUA_LOGIN,SGD_CIU_CEDULA DOCUMENTO,'' DEPE_CODI,'' CODI_NIVEL,'' USUA_CODI,SGD_CIU_NOMBRE NOMBRE, SGD_CIU_APELL1 APELLIDO1, SGD_CIU_APELL2 APELLIDO2,SGD_CIU_EMAIL EMAIL
               , SGD_CIU_DIRECCION, SGD_CIU_TELEFONO , SGD_CIU_CODIGO
            FROM SGD_CIU_CIUDADANO WHERE $whereTipoBusqueda0";
   break;
  case 2:
   $iSql = "SELECT '' USUA_LOGIN,SGD_OEM_NIT DOCUMENTO,'' DEPE_CODI,'' CODI_NIVEL,'' USUA_CODI,SGD_OEM_OEMPRESA NOMBRE, SGD_OEM_REP_LEGAL APELLIDO1, SGD_OEM_SIGLA APELLIDO2, SGD_OEM_EMAIL EMAIL
               , SGD_OEM_DIRECCION DIRECCION, SGD_OEM_TELEFONO TELEFONO, SGD_OEM_CODIGO
            FROM SGD_OEM_OEMPRESAS WHERE $whereTipoBusqueda2"; 
   break;
  case 6:
   $iSql = "SELECT USUA_LOGIN,USUA_DOC DOCUMENTO,DEPE_CODI,CODI_NIVEL,USUA_CODI,USUA_NOMB NOMBRE,USUA_EMAIL EMAIL 
               , USUA_DOC codigo
            FROM USUARIO WHERE $whereTipoBusqueda6"; 
   break;
  }
    $rs=$this->db->query($iSql);
    if($rs && !$rs->EOF){
      $salida['login'     ] = $rs->fields["USUA_LOGIN"];
      $salida['documento' ] = $rs->fields["DOCUMENTO" ];
      $salida['depe_codi' ] = $rs->fields["DEPE_CODI" ];
      $salida['usua_nivel'] = $rs->fields["CODI_NIVEL"];
      $salida['usua_codi' ] = $rs->fields["USUA_CODI" ];
      $salida['nombre'    ] = $rs->fields["NOMBRE"    ];
      $salida['apellido1' ] = $rs->fields["APELLIDO1" ];
      $salida['apellido2' ] = $rs->fields["APELLIDO2" ];
      $salida['direccion' ] = $rs->fields["DIRECCION" ];
      $salida['telefono'  ] = $rs->fields["TELEFONO"  ];
      $salida['codigo'    ] = $rs->fields["CODIGO"    ];
      $salida['email'     ] = $rs->fields["EMAIL"     ];
    }else{
     return false;
   }
       return $salida;
    }
    else
    {
      throw new Exception("Error, llave de consulta no valida");
    }

} 


    /**
     * crear y editar usuario
     * @param  array son necesarios el id_usuario, el tipo de usuario y datos
     * @return bool
     */

    function usuarioCreaEdita($datos){
        // Usuario ....................................................................
        $idUser = intval($datos['id_table']); //Id del usuario
        
        if((!$idUser  or $idUser==0)  && $datos['cedula']){
        #$this->db->conn->debug = true;
          $codigoUser = $this->getInfoTercero($datos['cedula'],'','documento', $datos['sgdTrd']);
          if($codigoUser["codigo"]) $idUser=$codigoUser["codigo"];
        }
        switch ( $datos['sgdTrd'] ){
            case 0:
                if(empty($idUser)){
                    $nextval=$this->db->nextId("sec_ciu_ciudadano");

                    if ($nextval==-1){
					$this->db->log_error ("001-- $nurad ","No se encontro la secuencia sec_ciu_ciudadano");
                        $this->result[] = array( "error"  => 'No se encontr&oacute; la secuencia sec_ciu_ciudadano');
                        return false;
                    }
                } else {
                    $nextval = $idUser;
                }


               //Comprobar que siempre llege una direccion (Cambio especifico para CRA , pero funciona para todas las entidades)
                if ($user['direccion']==''){
                    $user['direccion'] = $user['email'];
                }

                if ($user['direccion']==''){
                    $user['direccion'] = 'indeterminada';
                }


                $record = array();
                $record['sgd_ciu_codigo']     = $nextval;
                $record['sgd_ciu_nombre']     = trim($datos['nombre']);
				        if(strlen($datos['apellido'])>=65){$datos['apellido']= substr($datos['apellido'],0,63);}
                $record['sgd_ciu_apell1']     = trim($datos['apellido']);
                $record['sgd_ciu_direccion']  = trim($datos['direccion']);
                $record['sgd_ciu_telefono']   = $datos['telef'];
                $record['sgd_ciu_email']      = trim($datos['email']);
                $record['sgd_ciu_cedula']     = $datos['cedula'];
                $record['tdid_codi']          = $datos['tdid_codi'];
                $record['muni_codi']          = $datos['muni_tmp'];
                $record['dpto_codi']          = $datos['dpto_tmp'];
                $record['id_cont']            = $datos['cont_tmp'];
                $record['id_pais']            = $datos['pais_tmp'];
                
                  $insertSQL = $this->db->conn->Replace("sgd_ciu_ciudadano",$record,'sgd_ciu_codigo',$autoquote = true);
   
                //Regresa 0 si falla, 1 si efectuo el update y 2 si no se
                //encontro el registro y el insert fue con exito
                if($insertSQL){
                    $this->result = $nextval;
                    return true;
                }else{
				  $this->db->log_error ("001-- $nurad ","Error en usuarioCreaEdita (usuario)",$record,1);
				}

                break;

            // Empresas ....................................................................
            case 2:
            
                $iSql = "select * from sgd_oem_oempresas where trim(sgd_oem_oempresa)='".$datos['nombre']."'";
                $rsOem = $this->db->conn->query($iSql);
                $this->sgdOemCodigo = $rsOem->fields["SGD_OEM_CODIGO"];
                if(empty($idUser)){
                    $nextval=$this->db->nextId("sgd_oem_oempresas");
                    // $this->sgdOemCodigo = $idUser;
                    if ($nextval==-1){
												$this->db->log_error ("001-- $nurad ","No se encontro la secuencia sgd_oem_oempresas");
                        $this->result = array( "error"  => 'No se encontr&oacute; la secuencia sgd_oem_oempresas');
                        return false;
                    }

                } else {
                    $nextval = $idUser;
                    $codigo = $idUser;
                    $this->sgdOemCodigo = $idUser;
                    $this->result = $codigo;
                    return true;
                }

                $record = array();
                $record['sgd_oem_codigo']     = $this->sgd_oem_codigo;
                $record['tdid_codi']          = $datos['tdid_codi'];
                $record['sgd_oem_oempresa']   = $datos['nombre'];
                $record['sgd_oem_rep_legal']  = $datos['apellido'];
                $record['sgd_oem_nit']        = $datos['cedula'];

                $record['sgd_oem_direccion']  = $datos['direccion'];
                $record['sgd_oem_telefono']   = $datos['telef'];
                $record['sgd_oem_email']      = $datos['email'];

                $record['muni_codi']          = $datos['muni_tmp'];
                $record['dpto_codi']          = $datos['dpto_tmp'];
                $record['id_cont']            = $datos['cont_tmp'];
                $record['id_pais']            = $datos['pais_tmp'];

                $insertSQL = $this->db->conn->Replace("sgd_oem_oempresas",$record,'sgd_ciu_codigo',$autoquote = true);

                if($insertSQL){
                    $this->result = $codigo;
                    return true;
                }else{
						$this->db->log_error ("001-- $nurad ","Error en usuarioCreaEdita Empresas",$record,1);
				}

				break;

		// Funcionario .................................................................
		case 6:
				$this->result = $idUser;
				return true;
				break;
		}
	}

    /**
     * Buscar todos lo usuarios de las dependencias seleccionadas
     * @param array dependencias seleccionadas
     * @return bool
     */
    public function usuariospordependencias($dependencia){

        $isql = " select
                        u.usua_nomb,
                        u.usua_codi
                      from
                        dependencia d,
                        usuario u
                      where
                        d.depe_codi = u.depe_codi and
                        d.depe_codi = $dependencia";

        $rs   = $this->db->conn->query($isql);

        while(!$rs->EOF){
            $this->result[] = $rs->fields;
            $rs->MoveNext();
        }

        return true;
    }

    /**
     * Borrar usuario de sgd_dir_codigo
     * @param array parametros de usuarios a borrar
     * @param numero de radicado
     * @return bool
     */
    public function borrarUsuarioRadicado($user, $nurad){
        $isql = "DELETE FROM
                    sgd_dir_drecciones
                 WHERE
                    radi_nume_radi = $nurad and
                    sgd_dir_codigo = $user";

        $rs   = $this->db->conn->query($isql);
    }

    /**
     * Borrar usuario de sgd_dir_codigo
     * @param array parametros de usuarios a borrar
     * @param numero de radicado
     * @return bool
     */
    public function usuariosDelRadicado($nurad){

        $isql = "select
                    sgd_dir_codigo, sgd_dir_mail 
                 from
                    SGD_DIR_DRECCIONES
                 where
                    RADI_NUME_RADI = $nurad";
        //$this->db->conn->debug = true;
        $rs = $this->db->conn->query($isql);
        
        while(!$rs->EOF){
            $dirCodigo =  $rs->fields['SGD_DIR_CODIGO'];
            $codi[] = $rs->fields['SGD_DIR_CODIGO'];
            $dirMail[$dirCodigo] = $rs->fields['SGD_DIR_MAIL'];
            $dirNombre[$dirCodigo] = $rs->fields['SGD_DIR_NOMBRE'];
            $dirNomRemDes[$dirCodigo] = $rs->fields['SGD_DIR_NOMREMDES'];
            
            $rs->MoveNext();
        }
        $this->dirMail = $dirMail; 
        $this->dirNombre = $dirNombre; 
        $this->dirNomRemDes = $dirNomRemDes;
        $this->result = $codi;
        return true;
    }

    /**
     * consecutivo _ sgdTrd _ id_sgd_dir_dre _ id_table
     * 1) Un usuario nuevo (0_0_XX_XX)....
     * 2) Un usuario existente en el sistema, NO asociado a un radicado (0_0_XX_12)
     * 3) Un usuario existen (0_0_123_17) (0_0_327_123)
     *
     * @param  array parametros del usuario a crear
     * @return bool
     *
     */
    public function guardarUsuarioRadicado($user, $nurad){
      
        $idUser       = intval($user['id_table']); //Id del usuario
        $idInRadicado = intval($user['id_sgd_dir_dre']);//Id usuario registrado en radicado
        $sgdDirTipo = intval($user['sgdDirTipo']);
	      //$this->db->conn->debug = true;
        //Modificar o Crea un usuario
        if($this->usuarioCreaEdita($user)){
            $coduser = $this->result;
        }else{
		$this->db->log_error ("001-- $nurad ","Error usuarioCreaEdita no definido",$coduser,2);
		};

        //agregar usuario al radicado
        if(empty($idInRadicado)){
            $nextval = $this->db->nextId("sec_dir_drecciones");
            if ($nextval==-1){
			$this->db->log_error ("001-- $nurad ","No se encontro la secuencia para grabar el usuario seleccionado");
                $this->result[] = array( "error"  => 'No se encontr&oacute; la secuencia para grabar el usuario seleccionado');
                return false;
            }
        //Modificar usuario ya registrado
        }else{
            $nextval = $idInRadicado;
        }
        $record = array();

        #COMPROBAR QUE LAS VARIABLES LLEGEN COMPLETAS.
        if(strlen($user['email'])>=49){$user['email']= substr($user['email'],0,49);}
        if(strlen($user['direccion'])>=149){$user['direccion']= substr($user['direccion'],0,149);}
        if(strlen($user['telef'])>=49){$user['telef']= substr($user['telef'],0,49);}
        if(strlen($user['cedula'])>=14){$user['cedula']= substr($user['cedula'],0,14);}

        #COMPROBAR SI LLEGAN DATOS COMPLETOS DE MUNICIPIO

        $record['MUNI_CODI']         = $user['muni_tmp'];
        $record['DPTO_CODI']         = $user['dpto_tmp'];
        $record['ID_PAIS']           = $user['pais_tmp'];
        $record['ID_CONT']           = $user['cont_tmp'];
        $record['SGD_TRD_CODIGO']    = $user['sgdTrd']; // Tipo de documento

        $record['SGD_DIR_DIRECCION'] = $user['direccion'];
        $record['SGD_DIR_TELEFONO']  = $user['telef'];
        $record['SGD_DIR_TDOC']      = $user['tdid_codi'];
        $record['SGD_DIR_MAIL']      = $user['email'];
        $record['SGD_DIR_TIPO']      = $sgdDirTipo; //Este es el tipo de direcciones, uno es primera vez.
        $record['SGD_DIR_CODIGO']    = $nextval; // Identificador unico

//APELLIDO
  if(strlen($user['apellido'])>=499){$user['apellido']= substr($user['apellido'],0,499);}
  $sgd_dir_apellido_var  = $user['apellido'];
  $record['SGD_DIR_APELLIDO'] =  $sgd_dir_apellido_var;

//NOMBRE
	$sgd_dir_nombre_var = $user['nombre'];
	if(strlen($sgd_dir_nombre_var)>=139){$sgd_dir_nombre_var= substr($sgd_dir_nombre_var,0,139);}

        $record['SGD_DIR_NOMBRE']    = trim($sgd_dir_nombre_var);

############### COMPRUEBO QUE DEBO COLOCAR EN NOMREMDES
  if($user['dignatario']){

		$sgd_dir_nombre_var = $user['dignatario'];
		if(strlen($sgd_dir_nombre_var)>=499){$sgd_dir_nombre_var= substr($sgd_dir_nombre_var,0,499);}
        $record['SGD_DIR_NOMREMDES'] = $sgd_dir_nombre_var;

        }else{

		if(strlen($user['nombre'])>=139){$user['nombre']= substr($user['nombre'],0,139);}
    if(strlen($user['apellido'])>=499){$user['apellido']= substr($user['apellido'],0,499);}

      $sgd_dir_nombre_var = $user['nombre'];
      $sgd_dir_apellido_var  = $user['apellido'];

  	   // $record['SGD_DIR_NOMREMDES'] =  $sgd||%||_dir_nombre_var;
       // $record['SGD_DIR_APELLIDO'] =  $sgd_dir_apellido_var;
        $record['SGD_DIR_NOMREMDES'] = $sgd_dir_nombre_var.' '.$sgd_dir_apellido_var;
	}
################
        $record['SGD_DIR_DOC']       = empty($user['cedula'])?  '0' : $user['cedula'];

        $record['RADI_NUME_RADI']    = $nurad; // No de radicado
        $record['SGD_SEC_CODIGO']    = 0;

        //$this->db->conn->debug = true;
        switch ( $user['sgdTrd'] ){
            // Usuario ....................................................................
            case 0:
                $record['SGD_CIU_CODIGO']    = $coduser;
                break;

            // Empresas ....................................................................
            case 2:
                if($coduser) $coduser=0;
                $record['SGD_OEM_CODIGO']    = $coduser;
                break;

            // Funcionario .................................................................
            case 6:
                $record['SGD_DOC_FUN']       = $coduser;
                break;
        }
     if(!$record['SGD_OEM_CODIGO']) $record['SGD_OEM_CODIGO']=0;
     if(!$record['SGD_DOC_FUN'])    $record['SGD_DOC_FUN']=0;
     if(!$record['SGD_CIU_CODIGO']) $record['SGD_CIU_CODIGO']=0;
  //$this->db->conn->debug= true;

        $insertSQL =  $this->db->conn->Replace("SGD_DIR_DRECCIONES",
        $record,
        'SGD_DIR_CODIGO, RADI_NUME_RADI',
        $autoquote = true);

//      echo "->".$insertSQL; exit;

        if(!empty($insertSQL)){ 
            $this->result =  array( "state"  => true, "value" => $nextval);
            return true;
        }else{
                $record['SGD_DIR_CODIGO'] = $this->db->nextId("sec_dir_drecciones");
                //CREO UN NUEVO USUARIO PARA ESTE RADICADO EN SGD_DIR_DRECCIONES

                $insertSQL =  $this->db->conn->Replace("SGD_DIR_DRECCIONES",
                $record,
                'SGD_DIR_CODIGO, RADI_NUME_RADI',
                $autoquote = true);

                if(!empty($insertSQL)){
									$this->result =  array( "state"  => true, "value" => $nextval);
									return true;
                }else{

								  echo "No se puedo generar include/tx/usuario.php : $record"; exit;
								  var_dump ($record);
									$this->result = array( "error"  => 'No se puedo agregar usuario al radicado');
									return false;
                }
        }

    }

    /**
     * Funcion para modificar agregar usuarios a la
     * tabla y permitir su ingreso en la radicacion
     * Usada principalmente en la radicacion/new.php
     * @data array datos a procesar
     */
    function agregarUsuario($data){
        $this->setRadiResultJson($data);
        return $this->resRadicadoHtml();
    }

    /**
     * Modifica la variable de intercambio
     * @data array datos a procesar
     */
    function setRadiResultJson($data){
        $this->result = json_decode($data, true);
    }

    /**
     * Retorna un html que se integra con el codigo javascript escrito
     * en el modulo en que se implemente. Inicialmente esta funcion
     * esta hecha para radicacion incluida en New.php.
     *
     * @return html from $this->result
     *
     */
    public function resRadicadoHtml($nuevo = false){
        $select = "  SELECT
                       tdid_codi, tdid_desc
                     FROM tipo_doc_identificacion";

        $rsTdid = $this->db->conn->query($select);
        foreach ($this->result as $k => $result){
          $rsTdid->MoveFirst();
          $options = "";  
          while(!$rsTdid->EOF){
               
              $codi = $rsTdid->fields['TDID_CODI'];
              $desc = $rsTdid->fields['TDID_DESC'];
              $codiSelect = $result["TDID_CODI"];  // Tipo de Identificacion que trae por defecto el usuario que esta mostrando..
              if($codiSelect == $codi && !empty($codiSelect)){
                  $options .= "<option value=' $codi ' selected >$desc</option>";
              }else{
                  $options .= "<option value=' $codi '>$desc</option>";
              }
              $rsTdid->MoveNext();
          }
            $tipo = intval($result["TIPO"]);
            switch ( $tipo ) {
                case 0:
                case 2:
                    $codigo = $result["SGD_CIU_CODIGO"];
                    break;
                case 2:
                    $codigo = $result["SGD_OEM_CODIGO"];
                    break;
                case 6:
                    $codigo = $result["SGD_DOC_FUN"];
                    break;
            }

            if(empty($codigo) || $nuevo){
              $codigo = 'XX';
            }

            /**
             * Identificador para realizar transaccion y eventos desde
             * la pagina de radicacion, el identificador se compone por:
             * @tipo tipo de usuario (usuario, funcionario, empresa)
             * @codigo numero asignado en la respectiva tabla id
             * @codigo codigo grabado en la tabla sec_dir_drecciones
             * si esta vacio se grabara como nuevo.
            */

            //Si es un registro nuevo mostramos los campos para editar
            $idtx = $k.'_'.$result["TIPO"].'_'.$codigo.'_'.$result["CODIGO"];

            $incremental = $_SESSION['INCREMENTAL1']++;

            $html = '<td class="search-table-icon">
                        <div class="row-fluid">
                            <span class="inline widget-icon txt-color-red"
                              rel="tooltip"
                              data-placement="right"
                              data-original-title="Eliminar Usuario">
                              <i class="fa fa-minus"></i></span>
                        </div>
                        <input type="hidden" class="hide" name="usuario[]" value="'.$idtx.'">
                      </td>';

            $html .= '  <td>
                          <label name="inp_'.$idtx.'_tdid_codi" class="select">
                            <select name="'.$idtx.'_tdid_codi">
                                '.$options.'
                            </select>
                          </label>
                        </td>';

            if(empty($result["CEDULA"])){
                $html .= '<td>
                  <label name="inp_'.$idtx.'_ced" class="input">
                    <input type="text" name="'.$idtx.'_cedula" maxlength="13" value="'.$result["CEDULA"].'">
                  </label>
                </td>';
            }else{
                $html .= '<td>
                  <label name="inp_'.$idtx.'_ced" class="input">
                    <input readonly type="text" name="'.$idtx.'_cedula" maxlength="13" value="'.$result["CEDULA"].'">
                  </label>
                </td>';
            }

            $html .= '  <td>
                          <label name="inp_'.$idtx.'_nomb" class="input">
                            <input type="text" name="'.$idtx.'_nombre" maxlength="149" value="'.$result["NOMBRE"].'">
                          </label>
                        </td>';

            if ($tipo!=6){
                $html .= '  <td>
                  <label name="inp_'.$idtx.'_apell" class="input">
                    <input type="text" name="'.$idtx.'_apellido" maxlength="49" value="'.$result["APELLIDO"].'">
                  </label>
                </td>';
            }else{
                $html .= '  <td>
                  <label name="inp_apell" class="input">
                    <input type="text" name="_apellido" value="" disabled style="background-color:#EAEAEA">
                  </label>
                </td>';
            }

            $html .= '  <td>
                          <label name="inp_'.$idtx.'_tel" class="input">
                            <input type="text" name="'.$idtx.'_telefono" maxlength="49" value="'.$result["TELEF"].'">
                          </label>
                        </td>';

            $html .= '  <td>
                          <label name="inp_'.$idtx.'_dire" class="input">
                            <input id="id_dir_'.$incremental.'" type="text" name="'.$idtx.'_direccion" maxlength="149" value="'.$result["DIRECCION"].'">
                          </label>
                        </td>';

            $html .= '  <td>
                          <label name="inp_'.$idtx.'_email" class="input">
                            <input id="id_ema_'.$incremental.'" type="text" name="'.$idtx.'_email" value="'.$result["EMAIL"].'">
                          </label>
                        </td>';

            $html .= '  <td>
                          <label name="inp_'.$idtx.'_digna" class="input">
                            <input type="text" name="'.$idtx.'_dignatario" value="'.$result["DIGNATARIO"].'">
                          </label>
                        </td>';

            $html .= '  <td class="toogletd">
                          <label name="inp_'.$idtx.'_pais" class="input">
                            <input type="text" name="'.$idtx.'_pais" value="'.$result["PAIS"].'">
                            <input type="hidden" name="'.$idtx.'_pais_codigo" value="'.$result["PAIS_CODIGO"].'">
                          </label>
                        </td>';

            $html .= '  <td class="toogletd">
                          <label name="inp_'.$idtx.'_dep" class="input">
                            <input type="text" name="'.$idtx.'_dep" value="'.$result["DEP"].'">
                            <input type="hidden" name="'.$idtx.'_dep_codigo" value="'.$result["DEP_CODIGO"].'">
                          </label>
                        </td>';

            $html .= '  <td class="toogletd">
                          <label name="inp_'.$idtx.'_muni" class="input">
                            <input type="text" name="'.$idtx.'_muni" value="'.$result["MUNI"].'">
                            <input type="hidden" name="'.$idtx.'_muni_codigo" value="'.$result["MUNI_CODIGO"].'">
                          </label>
                        </td>';

            $htmltotal .= '<tr name="item_usuario" >'.$html.'</tr>';
        }

        return $htmltotal;
    }



    public function usuarioPorRadicado($nurad) {
         $iSql = "
            select
                s.SGD_DIR_CODIGO    as codigo
              , s.SGD_DIR_NOMBRE    as nombre
              , s.SGD_DIR_APELLIDO  as apellido
              , s.SGD_DIR_NOMREMDES as dignatario
              , s.SGD_DIR_DIRECCION as direccion
              , s.SGD_DIR_TELEFONO  as telef
              , s.SGD_DIR_MAIL      as email
              , s.SGD_TRD_CODIGO    as tipo
              , s.SGD_DIR_DOC       as cedula
              , s.SGD_DIR_TDOC      as tipo_identificacion 
              , p.NOMBRE_PAIS       as pais
              , p.ID_PAIS           as pais_codigo
              , d.DPTO_NOMB         as dep
              , d.DPTO_CODI         as dep_codigo
              , m.MUNI_NOMB         as muni
              , m.MUNI_CODI         as muni_codigo
              , s.SGD_DIR_TDOC      as tdid_codi
              , s.SGD_DOC_FUN
              , s.SGD_OEM_CODIGO
              , s.SGD_CIU_CODIGO
            from
                sgd_dir_drecciones s
              , DEPARTAMENTO d
              , MUNICIPIO m
              , SGD_DEF_PAISES p
            where
              m.id_pais  =  s.id_pais
              and m.muni_codi = s.muni_codi
              and m.dpto_codi = d.dpto_codi
              and d.dpto_codi = s.dpto_codi
              AND p.id_pais   = d.id_pais
              and p.id_pais   = s.id_pais
              and d.id_pais   = s.id_pais
              and s.radi_nume_radi = $nurad
          ";

        $rs = $this->db->conn->query($iSql);
        while(!$rs->EOF){
            $this->result[] = $rs->fields;
            $rs->MoveNext();
        }

        return !empty($this->result) ? true : false;
    }


    public function buscarPorParametros($search) {
                $this->db->limit(24);
        $limitMsql = $this->db->limitMsql;
        $limitOci8 = $this->db->limitOci8;
        $limitPsql = $this->db->limitPsql;
        
        $tipo   = (is_array($search['tdoc']))? $search['tdoc']['value'] : $search['tdoc'];
        $docu   = (is_array($search['docu']))? $search['docu']['value'] : $search['docu'];
        $name   = trim((is_array($search['name']))? $search['name']['value'] : $search['name']);
        $tele   = (is_array($search['tele']))? $search['tele']['value'] : $search['tele'];
        $mail   = (is_array($search['mail']))? $search['mail']['value'] : $search['mail'];
        $codi   = (is_array($search['codi']))? $search['codi']['value'] : $search['codi'];

        switch ( $tipo ) {

            // Usuario ........................................................
            case 0:

                if(!empty($name)){

                    $where = "(UPPER(s.SGD_CIU_NOMBRE) LIKE '%". strtoupper($name) ."%' OR
                                                       UPPER(s.SGD_CIU_APELL1) LIKE '%". strtoupper($name) ."%' OR
                                                       UPPER(s.SGD_CIU_APELL2) LIKE '%". strtoupper($name) ."%')" ;
                    $name = str_replace(' ', '%', trim($name));
                    $where = "UPPER(concat(s.SGD_CIU_NOMBRE,' ',s.SGD_CIU_APELL1,' ',s.SGD_CIU_APELL2)) LIKE '%". strtoupper($name) ."%' ";                    
                }

                if(!empty($docu)){

                    $sub    = " UPPER(SGD_CIU_CEDULA)  LIKE '%$docu%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);

                }


                if(!empty($tele)){
                    $sub    = " UPPER(SGD_CIU_TELEFONO) LIKE '%$tele%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }

                if(!empty($mail)){
                    $sub    = " SGD_CIU_EMAIL   iLIKE '%$mail%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }

                if(!empty($codi)){
                    $sub    = " UPPER(SGD_CIU_CODIGO)   LIKE '%$codi%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }

                $concatApell = $this->db->conn->Concat('s.SGD_CIU_APELL1',"' '",'s.SGD_CIU_APELL2');
                if($this->db->Driver == "postgres"){
                $concatApell = " concat(s.SGD_CIU_APELL1,' ',s.SGD_CIU_APELL2)";
                }
    $concatApell = " concat(s.SGD_CIU_APELL1,' ',s.SGD_CIU_APELL2)";
    #LLAMO UNA FUNCIÓN CONNECCIÓN HANDLER LLAMADA LIMIT LA CUAL ME DEVUELVE LA ESTRUCTURA CORRECTA
    $this->db->limit(24);
    $limitMsql = $this->db->limitMsql;
    $limitOci8 = $this->db->limitOci8;
    $limitPsql = $this->db->limitPsql;

		#COMPRUEBO QUE BASE DE DATO USA, PARA COMPROBAR SI NECESITA UN ALIAS EL GRUPO O NO
		$this->db->getDriver();
		if($this->db->Driver == "postgres"){$__as = "AS usertables"; }
		if($this->db->Driver == "oci8"){ $__as = "";}
		$isql = "
		SELECT $limitMsql DISTINCT
			 codigo
			,nombre
			,direccion
			,telef
			,email
			,cedula
			,pais
			,pais_codigo
			,dep
			,dep_codigo
			,muni
			,muni_codigo
			,tipo
			,tdid_codi
			,apellido

		FROM (
		SELECT DISTINCT 
		 s.SGD_CIU_CODIGO    as codigo
		,s.SGD_CIU_NOMBRE    as nombre
		,s.SGD_CIU_DIRECCION as direccion
		,s.SGD_CIU_TELEFONO  as telef
		,s.SGD_CIU_EMAIL     as email
		,s.SGD_CIU_CEDULA    as cedula
		,p.NOMBRE_PAIS       as pais
		,p.ID_PAIS           as pais_codigo
		,d.DPTO_NOMB         as dep
		,d.DPTO_CODI         as dep_codigo
		,m.MUNI_NOMB         as muni
		,m.MUNI_CODI         as muni_codigo
		,0                   as tipo
		,s.TDID_CODI         as tdid_codi
		,$concatApell as apellido
		FROM
		SGD_CIU_CIUDADANO s
		,DEPARTAMENTO d
		,MUNICIPIO m
		,SGD_DEF_PAISES p
		WHERE
		$where
      and m.id_pais   =  s.id_pais
      and m.muni_codi = s.muni_codi
      and m.dpto_codi = s.dpto_codi
      and d.dpto_codi = s.dpto_codi
      AND p.id_pais   = d.id_pais
      and p.id_pais   = s.id_pais
      and d.id_pais   = s.id_pais
	
		ORDER BY nombre, apellido
	
		) $__as $limitPsql $limitOci8 ";

             break;

//////////////////--------------------

            // Empresas ....................................................................
            case 1:

                if(!empty($name)){
                    $where = $this->db->conn->Concat( "(UPPER(NOMBRE_DE_LA_EMPRESA) LIKE '%". strtoupper($name) ."%'
                                      OR UPPER(SIGLA_DE_LA_EMPRESA) LIKE '%". strtoupper($name) ."%')");
                }

                if(!empty($docu)){
                    $sub    = " UPPER(NIT_DE_LA_EMPRESA) LIKE '%$docu%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }


                if(!empty($tele)){
                    $sub    = " UPPER(TELEFONO_1) LIKE '%$tele%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }

                if(!empty($mail)){
                    $sub    = " UPPER(EMAIL)   LIKE '%$mail%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }

                if(!empty($codi)){
                    $sub    = " UPPER(NUIR)   LIKE '%$codi%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }
	 //$concatSigla = $this->db->conn->Concat('s.sgd_oem_sigla',"' '",'s.nombre_rep_legal');

		#LLAMO UNA FUNCIÓN CONNECCIÓN HANDLER LLAMADA LIMIT LA CUAL ME DEVUELVE LA ESTRUCTURA CORRECTA
                 $this->db->limit(24);
                 $limitMsql = $this->db->limitMsql;
                 $limitOci8 = $this->db->limitOci8;
                 $limitPsql = $this->db->limitPsql;


                $isql = "SELECT

                   s.nuir    AS codigo
                  ,s.nombre_de_la_empresa  AS nombre
                  ,s.direccion AS direccion
                  ,s.telefono_1  AS telef

                  ,s.email     AS email
                  ,s.nit_de_la_empresa AS cedula
                  ,p.NOMBRE_PAIS       as pais
                  ,p.ID_PAIS           as pais_codigo

                  ,d.DPTO_NOMB         as dep
                  ,d.DPTO_CODI         as dep_codigo
                  ,m.MUNI_NOMB         as muni
                  ,m.MUNI_CODI         as muni_codigo
                  ,2                   as tipo
                  ,s.nit_de_la_empresa as tdid_codi
                  ,s.nombre_rep_legal AS apellido
                FROM
                  BODEGA_EMPRESAS s

                  ,DEPARTAMENTO d
                  ,MUNICIPIO m
                  ,SGD_DEF_PAISES p
                WHERE
                  $where
                  and m.muni_codi = TO_NUMBER(s.codigo_del_municipio)
                  and m.dpto_codi = TO_NUMBER (s.codigo_del_departamento)
                  and d.dpto_codi = TO_NUMBER( s.codigo_del_departamento)
                  and p.id_pais   = s.id_pais
                  and p.id_cont   = s.id_cont
                  and d.id_pais   = s.id_pais
                  and d.id_cont   = s.id_cont "
		  . $limitMsql."  ".$limitOci8;

                break;

/////////////////////////-----------------------------






            // Empresas ....................................................................
            case 2:

                if(!empty($name)){
                    $where = $this->db->conn->Concat( "(UPPER(SGD_OEM_OEMPRESA) LIKE '%". strtoupper($name) ."%'
                                      OR UPPER(SGD_OEM_SIGLA) LIKE '%". strtoupper($name) ."%')");
                }

                if(!empty($docu)){
                    $sub    = " UPPER(SGD_OEM_NIT) LIKE '%$docu%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }


                if(!empty($tele)){
                    $sub    = " UPPER(SGD_OEM_TELEFONO) LIKE '%$tele%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }

                if(!empty($mail)){
                    $sub    = " UPPER(SGD_OEM_EMAIL)   LIKE '%$mail%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }

                if(!empty($codi)){
                    $sub    = " UPPER(SGD_OEM_CODIGO)   LIKE '%$codi%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }
	 $concatSigla = $this->db->conn->Concat('s.sgd_oem_sigla',"' '",'s.SGD_OEM_REP_LEGAL');

		#LLAMO UNA FUNCIÓN CONNECCIÓN HANDLER LLAMADA LIMIT LA CUAL ME DEVUELVE LA ESTRUCTURA CORRECTA
        $this->db->limit(24);
        $limitMsql = $this->db->limitMsql;
        $limitOci8 = $this->db->limitOci8;
        $limitPsql = $this->db->limitPsql;


      $isql = "SELECT
          s.sgd_oem_codigo    AS codigo
        ,s.sgd_oem_oempresa  AS nombre
        ,s.sgd_oem_direccion AS direccion
        ,s.sgd_oem_telefono  AS telef
        ,s.sgd_oem_email     AS email
        ,s.sgd_oem_nit       AS cedula
        ,p.NOMBRE_PAIS       as pais
        ,p.ID_PAIS           as pais_codigo
        ,d.DPTO_NOMB         as dep
        ,d.DPTO_CODI         as dep_codigo
        ,m.MUNI_NOMB         as muni
        ,m.MUNI_CODI         as muni_codigo
        ,2                   as tipo
        ,s.TDID_CODI         as tdid_codi
        ,$concatSigla AS apellido
      FROM
        SGD_OEM_OEMPRESAS s
        ,DEPARTAMENTO d
        ,MUNICIPIO m
        ,SGD_DEF_PAISES p
      WHERE
        $where
        and m.muni_codi = s.muni_codi
        and m.dpto_codi = s.dpto_codi
        and d.dpto_codi = s.dpto_codi
        and p.id_pais   = s.id_pais
        and p.id_cont   = s.id_cont
        and d.id_pais   = s.id_pais
        and d.id_cont   = s.id_cont "
				;

               /*  ORDER  BY sgd_oem_oempresa ";

                   switch ($db->driver)
                     {case 'mssql': $isql= $isql."  LIMIT 24"; break;
                      case 'oracle': $isql= $isql."  ROWNUM<=24"; break;
                      case 'oci8':   $isql= $isql."  ROWNUM<=24"; break;
                      case 'postgres': $isql= $isql."  LIMIT 24"; break;} */

                break;

            case 6:
                // Funcionario ..............................................................................
                
                if(!empty($name)){
                    $name = str_replace(' ', '%', $name);
                    $where = $this->db->conn->Concat(" UPPER(s.USUA_NOMB) LIKE '%". strtoupper($name) ."%'");
                }

                if(!empty($docu)){
                    $sub    = " cast(s.usua_doc as varchar(15)) LIKE '%$docu%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }


                if(!empty($tele)){
                    $sub    = ( "UPPER(s.USU_TELEFONO1) LIKE '%". strtoupper($tele) ."%'
                      OR UPPER(s.usua_ext)   LIKE '%". strtoupper($tele) ."%'");
                    $where .= (empty($where))? $sub : ' and '. $sub;
                }

                if(!empty($mail)){
                    $sub    = " UPPER(s.USUA_EMAIL)   LIKE '%$mail%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }

                if(!empty($codi)){
                    $sub    = " UPPER(id)   LIKE '%$codi%'";
                    $where .= (empty($where))? $sub : ' and '. strtoupper($sub);
                }
	 $concatTelef = $this->db->conn->Concat('s.usu_telefono1',"' '",'s.usua_ext');

		#COMPRUEBO QUE BASE DE DATO USA, PARA COMPROBAR SI NECESITA UN ALIAS EL GRUPO O NO
		$this->db->getDriver();
		if($this->db->Driver == "postgres"){$__as = "AS talbeuser"; }
		if($this->db->Driver == "oci8"){ $__as = "";}

    $isql = "
        SELECT DISTINCT
           codigo
          ,nombre
          ,direccion
          ,telef
          ,email
          ,cedula
          ,pais
          ,pais_codigo
          ,dep
          ,dep_codigo
          ,muni
          ,muni_codigo
          ,tipo
          ,tdid_codi
          ,apellido
      FROM (
            SELECT DISTINCT
                s.id           AS codigo
              ,s.usua_nomb    AS nombre
              ,dp.depe_nomb   AS direccion
              ,s.usua_email   AS email
              ,s.usua_doc     AS cedula
              ,''   AS apellido
              ,$concatTelef   AS telef
              ,p.NOMBRE_PAIS  as pais
              ,p.ID_PAIS      as pais_codigo
              ,d.DPTO_NOMB    as dep
              ,d.DPTO_CODI    as dep_codigo
              ,m.MUNI_NOMB    as muni
              ,m.MUNI_CODI    as muni_codigo
              ,6              as tipo
              ,0              as tdid_codi
            FROM
              USUARIO s
              ,DEPARTAMENTO d
              ,MUNICIPIO m
              ,SGD_DEF_PAISES p
              ,DEPENDENCIA dp
            WHERE
              $where
              and s.usua_esta  = '1'
              and d.dpto_codi  = dp.dpto_codi
              and m.muni_codi  = dp.muni_codi
              and m.dpto_codi  = d.dpto_codi
              and dp.depe_codi = s.depe_codi
              and p.id_pais    = s.id_pais
              and p.id_cont    = s.id_cont
              and m.id_pais  =  s.id_pais
              and d.ID_PAIS = s.ID_PAIS
            ORDER  BY usua_nomb
            ) $__as  ";

                break;
        }
        //$this->db->conn->debug = true;

        //if(!empty($where)) 
        //{
          $rs = $this->db->conn->query($isql);

          while(!$rs->EOF ){
              $this->result[] = $rs->fields;
              $rs->MoveNext();
          }
        //}
        //return true;
        return !empty($this->result) ? true : false;

    }
}
