<?php
/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

SIIM2 Models are the data definition of SIIM2 Information System
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

class Roles {
    /*** Attributes:
     * Clase que maneja los usuarios
     */

    var $db; //Conexion a la base de datos
    var $id;
    var $users;
    var $permisos;
    var $opciones;
    var $grupos;
    var $usuario;
    var $email;
    var $usuarios;
    var $dependencias;
    var $membresias;
    var $permisosUsuario;
    var $error;


    function __construct($db){
        $this->db=$db;
        $this->db->conn->debug=false;
    }

    /**
     * retornar Permisos
     * @return bool
     */
    public function retornarPermisos(){
         /*Desarrollo para ANM , pero no afecta a ninguna otra entidad*/
        if($_SESSION['USUA_LESS_PERM_USER']!='' or $_SESSION["USUA_LESS_PERM_USER_PROFILE"]!=''){ $_coindition = ' where id <> 271 and id <> 272'; }

        $sql_perm = " SELECT
                          id,
                          nombre,
                          crud,
                          descripcion
                      FROM
                          autp_permisos ".$_coindition;

        $perm     = $this->db->conn->query($sql_perm);

        if($perm->EOF){
            return false;
        }

        while(!$perm->EOF){
            $grupPer = array();
            $idperm = $perm->fields['ID'];

            $sql_perm_grup = " SELECT
                                  autg_id
                               FROM
                                  autr_restric_grupo
                               where
                                  autp_id = '$idperm'";

            $perm_grup     = $this->db->conn->query($sql_perm_grup);

            while(!$perm_grup->EOF){
                $grupPer[] = $perm_grup->fields['AUTG_ID'];
                $perm_grup->MoveNext();
            }

            $this->permisos[] = array(
            'ID'          => $idperm,
            'NOMBRE'      => $perm->fields['NOMBRE'],
            'CRUD'        => $perm->fields['CRUD'],
            'AUTG_ID'     => $grupPer,
            'DESCRIPCION' => $perm->fields['DESCRIPCION']);

            $perm->MoveNext();
        }


        return true;
    }


    /**
     * retornar Opciones
     * @return array de opciones
     */
    public function retornarOpcionesPermisos(){
        return array(  array('ID' => 0, 'NOMBRE' => '0 - Ninguno'),
                       array('ID' => 1, 'NOMBRE' => '1 - Leer'),
                       array('ID' => 2, 'NOMBRE' => '2 - Editar'),
                       array('ID' => 3, 'NOMBRE' => '3 - Crear y Borrar'),
                       array('ID' => 4, 'NOMBRE' => '4 - Nivel_1'),
                       array('ID' => 5, 'NOMBRE' => '5 - Nivel_2')
                    );
    }


    /**
     * Retorna Grupos
     * @return bool, carga variable de grupos
     */
    public function retornarGrupos(){
        /*Desarrollo para ANM , pero no afecta a ninguna otra entidad*/
        if($_SESSION['USUA_LESS_PERM_USER']!='' or $_SESSION["USUA_LESS_PERM_USER_PROFILE"]!=''){ $_coindition = ' where id <> 186'; }

        $sql_grup = " SELECT
                          id,
                          nombre,
                          descripcion
                      FROM
                          autg_grupos ".$_coindition;

        $grup = $this->db->conn->query($sql_grup);

        if($grup->EOF){
            return false;
        }

        while (!$grup->EOF) {
            $this->grupos[] = $grup->fields;
            $grup->MoveNext();
        }

        return true;

    }


    /**
     * validar ldap
     * @param  string nombre del usuario a retornar
     * @return bool, carga variable de usuarios
     */
    public function activoLdap($usuario=false){

        $usuario = strtoupper($usuario);

        $isql = " SELECT
                    c.crud,
                    s.usua_email as email
                  FROM
                    autm_membresias a,
                    autr_restric_grupo b,
                    autp_permisos c,
                    usuario s
                  where
                    b.autg_id = a.autg_id and
                    b.autp_id = c.id and
                    s.id = a.autu_id and
                    s.usua_login like '$usuario' and
                    c.nombre like 'USUA_AUTH_LDAP' and c.crud=0
			";

        $usua = $this->db->conn->query($isql);

        if($usua->fields['CRUD']==''){
            $isql2 = " SELECT
                    c.crud,
                    s.usua_email as email
                  FROM
                    autm_membresias a,
                    autr_restric_grupo b,
                    autp_permisos c,
                    usuario s
                  where
                    b.autg_id = a.autg_id and
                    b.autp_id = c.id and
                    s.id = a.autu_id and
                    s.usua_login like '$usuario' and
                    c.nombre like 'USUA_AUTH_LDAP' and c.crud=1
                        ";

	        $usua2 = $this->db->conn->query($isql2);
		if($usua2->fields['CRUD']==''){
			return false;
	        }
        	else{
                	$this->email  = $usua2->fields['EMAIL'];
	                return true;
		}
        }
	else{
		return false;
	}
    }

    /**
     * Retorna usuarios
     * @param  string nombre del usuario a retornar
     * @return bool, carga variable de usuarios
     */
    public function retornarUsuarios($usuario=false, $password=false){

        $sql_usua = "
                      SELECT
                          us.id,
                          us.usua_nomb  as nombres,
                          us.usua_doc  as documento,
                          us.usua_email as correo,
                          us.usua_login as usuario,
                          us.usua_esta  as estado,
                          us.usua_nuevo  as nuevo,
                          us.depe_codi  as depecodi,
                          dp.depe_nomb  as dependencia,
                          us.usua_codi as codigo_usuario
                      FROM
                          usuario  us,
                          dependencia  dp
                      where
                          dp.depe_codi = us.depe_codi";

        if(empty($usuario)){
            $usua = $this->db->conn->query($sql_usua);

            if($usua->EOF){
                return false;
            }

            while (!$usua->EOF) {
                //Todo requiere conversor para el password
                $this->usuarios[] = $usua->fields;
                $usua->MoveNext();
            }
        }else{
            $usuario = strtoupper($usuario);
            $sql_usua .= " AND usua_esta like '1' AND usua_login like '$usuario' ";

            if($password){
                $sql_usua  .= " AND (USUA_PASW ='".SUBSTR(md5($password),1,26)."' or USUA_NUEVO='0')";
            }

            $usua = $this->db->conn->query($sql_usua);

            if($usua->EOF){
                return false;
            }

            $this->usuario = $usua->fields;

        }

        return true;
    }


    /**
     * Retorna Dependencias
     * @return bool, carga variable de Dependencias
     */
    public function retornarDependencias(){
        $sql_depe = " SELECT
                        depe_nomb,
                        depe_codi
                      FROM
                        dependencia";

        $depe = $this->db->conn->query($sql_depe);

        if($depe->EOF){
            return false;
        }

        while (!$depe->EOF) {
            $this->dependencias[] = $depe->fields;
            $depe->MoveNext();
        }

        return true;
    }


    /**
     * Retorna Membresias
     * @return bool, carga variable de membresias
     */
    public function retornarMembresias(){
        $sql_memb = "SELECT
                        id,
                        autg_id,
                        autu_id
                      FROM
                        autm_membresias";

        $memb = $this->db->conn->query($sql_memb);

        if($memb->EOF){
            return false;
        }

        while (!$memb->EOF) {
            $this->membresias[] = $memb->fields;
            $memb->MoveNext();
        }

        return true;
    }



    /**
     * crear Grupo
     * @param  string nombre del grupo
     * @param  string descripcion delgrupo
     * @param  integer id del grupo
     * @return bool
    */
    public function creaEditaGrupo($nombre, $descripcion, $id){
        if($id){
            $nextval    = $id;
        }else{
            $sql_sel_id = "SELECT max(id) AS ID FROM autg_grupos";
            $sql_sel    = $this->db->conn->query($sql_sel_id);
            $nextval    = $sql_sel->fields["ID"] + 1;
        }

        $record = array();
        $record['id']           = $nextval;
        $record['nombre']       = $nombre;
        $record['descripcion']  = $descripcion;

        $insertSQL = $this->db->conn->Replace("autg_grupos",$record,'id',$autoquote = true);
        if(empty($insertSQL)){
            return false;
        }else{
            $this->id = $nextval;
            return true;
        }
    }




    /**
     * Borrar Grupo
     * @param  integer id del grupo
     * @return bool
     */
    public function borrarGrupo($id){
        $sql_sel_id = "delete from autg_grupos where id = $id";
        $sql_sel    = $this->db->conn->query($sql_sel_id);

        if(!$sql_sel->EOF){
            return false;
        }else{
            return true;
        }
    }


    /**
     * Crear y Edita Permisos
     * @param  string nombre del permiso
     * @param  string descripcion del permiso
     * @param  string dependencia del permiso
     * @param  string crud del permiso
     * @param  string grupo delpermiso
     * @param  integer id del permiso
     * @return bool
     */

    public function creaEditaPermiso($nombre, $descripcion, $crud, $grupo, $id){
        if($id){
            $nextval    = $id;
        }else{
            $sql_sel_id = "SELECT max(id) AS ID FROM autp_permisos";
            $sql_sel    = $this->db->conn->query($sql_sel_id);
            $nextval    = $sql_sel->fields["ID"] + 1;
        }

        $record = array();
        $record['id']          = $nextval;
        $record['nombre']      = $nombre;
        $record['descripcion'] = $descripcion;
        $record['crud']        = $crud;

        $insertSQL = $this->db->conn->Replace("autp_permisos",$record,'id',$autoquote = true);
        if(empty($insertSQL)){
            return false;
        }else{
            $this->id = $nextval;

            $del_sql = "delete from autr_restric_grupo where autp_id = '$nextval'";
            $this->db->conn->query($del_sql);

            foreach (explode(",",$grupo) as $value) {
                $sql_sel_id = "SELECT max(id) AS ID FROM autr_restric_grupo";
                $sql_sel    = $this->db->conn->query($sql_sel_id);
                $valnext    = $sql_sel->fields["ID"] + 1;

                $registro            = array();
                $registro['id']      = $valnext;
                $registro['autg_id'] = $value;
                $registro['autp_id'] = $nextval;

                $insertSQL = $this->db->conn->Replace("autr_restric_grupo",$registro,'autg_id, autg_id',
                    $autoquote = true);

                if(empty($insertSQL)){
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * Borrar Permiso
     * @param  integer id del permiso
     * @return bool
     */
    public function borrarPermiso($id){
        $sql_sel_id = "delete from autp_permisos where id = $id";
        $sql_sel    = $this->db->conn->query($sql_sel_id);

        if(!$sql_sel->EOF){
            return false;
        }else{
            return true;
        }
    }


    /**
     * Crear y Editar Usuarios
     * @param  string nombre del usuario
     * @return bool
     */

    public function creaEditaUsuario($usuario, $nombres, $nuevo, $correo, $estado, $depe, $id, $documento){
		$usuario=strtoupper($usuario);

        $record = array();

        if($id){
		
		$updatedepen = "";

		#SE COMPRUEBA QUE DEPENDENCIA TIENE EL USUARIO	
		$sql = "SELECT DEPE_CODI, USUA_CODI,USUA_ESTA,USUA_NUEVO FROM USUARIO WHERE ID = $id";
	        $SelectSQL = $this->db->conn->query($sql);
	
	         if($SelectSQL->EOF){
        	     return false;
	         }
 
        	 while (!$SelectSQL->EOF) {
	             $Dependencia = $SelectSQL->fields["DEPE_CODI"];
		     $CodigoUsuario = $SelectSQL->fields["USUA_CODI"];
		     $Nuevo = $SelectSQL->fields["USUA_NUEVO"];
		     $Estado = $SelectSQL->fields["USUA_ESTA"];
	             $SelectSQL->MoveNext();
	         }
		
		#SE COMPRUEBA SI LA DEPENDENCIA QUE TIENE EL USUARIO ES DIFERENTE A LA DEPENDENCIA NUEVA
		if ($Dependencia != $depe or $Estado!=$estado ){
			#COMPROBAMOS SI TIENEN RADICADOS EN LAS CUENTAS
  $sql = "select count(r.radi_nume_radi) num		
				FROM RADICADO r 
				INNER JOIN USUARIO b ON r.RADI_USUA_ACTU=b.USUA_CODI AND r.RADI_DEPE_ACTU=b.DEPE_CODI  
				LEFT OUTER JOIN SGD_TPR_TPDCUMENTO t ON r.tdoc_codi=t.SGD_TPR_CODIGO
				WHERE 	
				b.DEPE_CODI= $Dependencia AND 
				r.RADI_DEPE_ACTU= $Dependencia   AND 
				b.USUA_CODI = $CodigoUsuario   
				";

			$rs_ = $this->db->conn->query($sql);
			$count = $rs_->fields['NUM'];
			
			#SI NO TIENE RADICADOS EN BANDEJA SE PROCEDE A ACTUALIZAR
			if ($count == 0){	
			$updatedepen = " , depe_codi = $depe ";
			}else{
				$this->error ="El Usuario Posee radicados en su bandeja";
				return false;
			}	

		}

            $nextval    = $id;

           $sql = "UPDATE usuario SET usua_nomb  = '$nombres',
                                       usua_email = '$correo',
                                       usua_esta  = '$estado',
                                       usua_nuevo = '$nuevo'
					$updatedepen
                                    where id=$nextval";
            
		$insertSQL = $this->db->conn->query($sql);

            if(empty($insertSQL)){
                return false;
                
            }else{
                $this->id = $nextval;
                return true;
                
            }

        } else {
            $sql_sel_id = "SELECT max(id) AS ID FROM usuario";
            $sql_sel    = $this->db->conn->query($sql_sel_id);
            $nextval    = $sql_sel->fields["ID"] + 1;
            $dates      = $this->db->conn->DBTimeStamp(time());

            if(empty($usuario) || empty($nombres) ||
                empty($correo) || empty($depe)){
                return false;
            }

            $sql = "insert into usuario (id,
                                         usua_codi,
                                         usua_login,
                                         depe_codi,
                                         usua_nomb,
                                         usua_doc,
                                         usua_email,
                                         usua_esta,
                                         usua_nuevo,
                                         usua_pasw,
                                         usua_fech_crea) ";
            $sql .= "values ($nextval,
                            $nextval,
                           '$usuario',
                            $depe,
                           '$nombres',
                           '$documento',
                           '$correo',
                            1,
                            0,
                           '02cb962ac59075b964b07152d2',
                           $dates)";
        }

        $insertSQL = $this->db->conn->query($sql);

        if(empty($insertSQL)){
            return false;
        }else{
            $this->id = $nextval;
            return true;
        }
    }

    /**
     * Borrar Permiso
     * @param  integer id del permiso
     * @return bool
     */
    public function borrarUsuario($id){
        $sql_sel_id = "delete from autu_usuarios where id = $id";
        $sql_sel    = $this->db->conn->query($sql_sel_id);

        if(!$sql_sel->EOF){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Buscar usuarios del grupo
     * @param  integer id del grupo
     * @return bool
     */
    public function buscarUsuariosGrupo($grupo){
        $sql_usu_id = "SELECT autu_id FROM autm_membresias where autg_id = $grupo";
        $sql_usu    = $this->db->conn->query($sql_usu_id);

        if(!$sql_usu->EOF){
            while (!$sql_usu->EOF && $sql_usu!=false) {
                $this->users[] = $sql_usu->fields['AUTU_ID'];
                $sql_usu->MoveNext();
            }
            return true;
        }else{
            return false;
        }
    }


    /**
     * Modificar Membresia
     * @param  integer id del grupo
     * @return array id's usuarios
     */
    public function modificarMembresia($grupo,$usuario,$estado){
        if(filter_var($estado, FILTER_VALIDATE_BOOLEAN)){

            $sql_sel_id = "SELECT max(id) AS ID FROM autm_membresias";
            $sql_sel    = $this->db->conn->query($sql_sel_id);
            $nextval    = $sql_sel->fields["ID"] + 1;

            $record = array();
            $record['id']       = $nextval;
            $record['autg_id']  = $grupo;
            $record['autu_id']  = $usuario;

            $insertSQL = $this->db->conn->Replace("autm_membresias",$record,'autg_id, autu_id',$autoquote = true);
//            if($grupo==7){
//                    $sql_up = "update usuario set sgd_rol_codigo=2 where id=".$usuario;
//                    $sql_sel    = $this->db->conn->query($sql_up);
//                }

            if($insertSQL->EOF){
                return false;
            }else{
                return true;
            }
        }else{
            $sql_sel_id = "delete from autm_membresias where autg_id = $grupo and autu_id = $usuario";
            $sql_sel    = $this->db->conn->query($sql_sel_id);
//            if($grupo==7){
//                    $sql_up = "update usuario set sgd_rol_codigo=0 where id=".$usuario;
//                    $sql_sel    = $this->db->conn->query($sql_up);
//            }

            if(empty($sql_sel)){
                return false;
            }else{
                return true;
            }
        }

    }

    /**
     * listado de  permisos para el usuario
     * @param  string nombre del usuario
     * @return bool, cargar variable de permisos del usuario
     */

    public function listadoDePermisosPorUsuario($usuario){
        if(!empty($usuario)){
            $id    = $this->usuario['ID'];
            //Todo se debe agregar las validaciones y encriptacion correspondiente
            //para tener el metodo seguro de ingreso
            $sql_perm = " SELECT
                            c.nombre,
                            c.crud,
                            c.descripcion
                          FROM
                            autm_membresias a,
                            autr_restric_grupo b,
                            autp_permisos c
                          where
                            a.autu_id = $usuario and
                            b.autg_id = a.autg_id and
                            b.autp_id = c.id";

            $sql = $this->db->conn->query($sql_perm);

            if(!$sql->EOF){
                while (!$sql->EOF && $sql!=false){
                    $llave = $sql->fields['NOMBRE'];
                    $crud  = $sql->fields['CRUD'];
                    $descp = $sql->fields['DESCRIPCION'];
                    $this->permisosUsuario[] = array('crud' => $crud,  'descripcion' => $descp,'nombre' => $llave);
                    $sql->MoveNext();
                }
            }

            return true;

        }else{

            return false;

        }
    }

    /**
     * Valida el usuario y retorna los permisos
     * @param  string nombre del usuario
     * @return bool, cargar variable de permisos del usuario
     */

    public function traerPermisos($usuario,  $password=false){
        if($this->retornarUsuarios($usuario, $password)){
            $id    = $this->usuario['ID'];
            //Todo se debe agregar las validaciones y encriptacion correspondiente
            //para tener el metodo seguro de ingreso
            $sql_perm = " SELECT
                            c.nombre,
                            c.crud,
                            c.descripcion
                          FROM
                            autm_membresias a,
                            autr_restric_grupo b,
                            autp_permisos c
                          where
                            a.autu_id = $id and
                            b.autg_id = a.autg_id and
                            b.autp_id = c.id";

            $sql = $this->db->conn->query($sql_perm);

            if(!$sql->EOF){
                while (!$sql->EOF && $sql!=false){
                    $llave = $sql->fields['NOMBRE'];
                    $crud  = $sql->fields['CRUD'];
                    $descp = $sql->fields['DESCRIPCION'];
                    $this->permisosUsuario[$llave] = array('crud' => $crud,  'descrp' => $descp);
                    $sql->MoveNext();
                }
            }

            return true;

        }else{

            return false;

        }
    }

    public function encr($string, $key) {
       $result = '';
       for($i=0; $i<strlen($string); $i++) {
          $char = substr($string, $i, 1);
          $keychar = substr($key, ($i % strlen($key))-1, 1);
          $char = chr(ord($char)+ord($keychar));
          $result.=$char;
       }
       return base64_encode($result);
    }

    public function dscr($string, $key) {
       $result = '';
       $string = base64_decode($string);
       for($i=0; $i<strlen($string); $i++) {
          $char = substr($string, $i, 1);
          $keychar = substr($key, ($i % strlen($key))-1, 1);
          $char = chr(ord($char)-ord($keychar));
          $result.=$char;
       }
       return $result;
    }

}
