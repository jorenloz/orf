<?php

error_reporting(0);

/**
 * Clase que administra o permite ralizar envios por empresa de mesajeria u otro
 * como email, etc...
 *
 * @access public
 * @author Correlibre - Jairo Losada
 * @version v 1.0
 */
class Envio
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---
   
    /**
     * Short description of attribute documento
     *
     * @access public
     * @var Integer
     * @var $usuaDoc integer  Documento de quein realiza la operacion.
     * @var $destino string Variable que indica el destino que puede ser una direccion física o electrónica. 
     * @var $db Objeto Base de datos instanciada.
     */
   
    var $destino; // @var $destino string Variable que indica el nombre de la ciudad destino que de un envio. 
    var $telefono; // 
    var $db;
    
    function __construct($db){
     $this->db = $db; 
    }
    
   
    public $documento = null;

    // --- OPERATIONS ---
    
    public function getUsuDoc(){
      
      return $valor;  
    }
    public function setUsuaDoc($valor){
      $this->usuaDoc = $valor;  
    }
    public function set($valor){
      $this->usuaDoc = $valor;  
    }
    public function setFormaEnvio($valor){
      $this->formaEnvio = $valor;  
    }
    public function setRadicadoAEnviar($valor){
      $this->radicadoAEnviar = $valor;  
    }    
    /** Metodo setDestino
      * @var $valor string Variable que indica el nombre de la ciudad  destino. 
      * 
      */
    public function setCiudadDestino($valor=''){
      $this->ciudadDestino = $valor;  
    }
    
    public function setTelefono($valor=''){
      $this->telefono = $valor;  
    }
    
    public function setMail($valor=''){
      $this->mail = $valor;  
    }
    
    public function setPeso($valor=0){
      $this->peso = $valor;  
    }    
    
    public function setValorUnitario($valor=0){
      $this->valorUnitario = $valor;  
    }    

    public function setNombre($valor=0){
      $this->nombre = $valor;  
    }

    /** Metodo setDirCodigo
      * Almacena el nombre de la ciudad destino del envio
      *
      * @var $valor integer codigo de la direccion en la tabla sgd_dir_drecciones. 
      * 
      */    
    public function setCodigoDir($valor){
      $this->codigoDir = $valor;  
    }

    /** Metodo setDirTipo
      * Almacena el codigo del tipo de direccion este indica el orden del remitente/destino de cada radicado.
      *
      * @var $valor string codigo orden de los destinatarios. 
      * 
      */    
    
    public function setDirTipo($valor){
      $this->dirTipo = $valor;  
    } 
    
    public function setCodigoDependencia($valor){
      $this->codigoDependencia = $valor;  
    }     

    /** Metodo setDirTipo
      * Almacena el número del primer radicado en el caso que el envío sea de una Radicación Masiva.
      *
      * @var $valor string Numero radicado de grupo de una masiva. 
      * 
      */    
    
    public function setRadicadoGrupoMasiva($valor=null){
      $this->RadicadoGrupoMasiva = $valor;  
    }     
    
    /** Metodo setNumeroPlanilla
      * Almacena el número de la planilla de envio en el cual se realiza la operación.  Se usa o no segun el tipo de envio.
      *
      * @var $valor string Numero de planilla de un envio. 
      * 
      */    
    
    public function setNumeroPlanilla($valor){
      $this->numeroPlanilla = $valor;  
    }
 
    public function setDireccion($valor){
      $this->direccion = $valor;  
    }       
    public function setNombreDepartamento($valor){
      $this->nombreDepartamento = $valor;  
    }       
    
    public function setNombreMunicipio($valor){
      $this->nombreMunicipio = $valor;  
    }           
    public function setNombrePais($valor){
      $this->nombrePais = $valor;  
    }         

    /** Metodo setObservacinoes
      * Almacena las observacinoes que se colocan al envio a realizar.
      *
      * @var $valor string Observacion. 
      * 
      */        
    public function setObservaciones($valor){
      $this->Observaciones = $valor;  
    }    
    

    /**
     * Short description of method generarEnvio
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @return mixed
     */
    public function generarEnvio()
    {
        // $this->db->conn->debug = true;
        $sql_sgd_renv_codigo = "select SGD_RENV_CODIGO FROM SGD_RENV_REGENVIO ORDER BY SGD_RENV_CODIGO DESC ";
        $rsRegenvio = $this->db->conn->SelectLimit($sql_sgd_renv_codigo,2);
        $nextval = $rsRegenvio->fields["SGD_RENV_CODIGO"];
        $nextval++;

        
        $record["USUA_DOC"] = $this->usuaDoc;
        $record["SGD_RENV_CODIGO"] = $nextval;
        $record["SGD_FENV_CODIGO"] = $this->formaEnvio;
        $record["SGD_RENV_FECH"] = $this->db->sysdate();
        $record["RADI_NUME_SAL"] = $this->radicadoAEnviar;
        $record["SGD_RENV_DESTINO"] = $this->ciudadDestino;
        $record["SGD_RENV_TELEFONO"] = $this->telefono;
        $record["SGD_RENV_MAIL"] = $this->mail;
        $record["SGD_RENV_PESO"] = $this->peso;
        $record["SGD_RENV_VALOR"] = $this->valorUnitario;
        $record["SGD_RENV_CERTIFICADO"] = 0;
        $record["SGD_RENV_ESTADO"] = 1;
        $record["SGD_RENV_NOMBRE"] = $this->nombre;
        $record["SGD_DIR_CODIGO"] = $this->codigoDir;
        $record["DEPE_CODI"] = $this->codigoDependencia;
        $record["SGD_DIR_TIPO"] = $this->dirTipo;
        if($this->RadicadoGrupoMasiva) $record["RADI_NUME_GRUPO"] = $this->RadicadoGrupoMasiva;
        $record["SGD_RENV_PLANILLA"] = $this->numeroPlanilla;
        $record["SGD_RENV_DIR"] = $this->direccion;
        $record["SGD_RENV_DEPTO"] = $this->nombreDepartamento;
        $record["SGD_RENV_MPIO"] = $this->nombreMunicipio;
        $record["SGD_RENV_PAIS"] = $this->nombrePais;
        $record["SGD_RENV_OBSERVA"] = $this->Observaciones;
        $record["SGD_RENV_CANTIDAD"] = 1;
       
        
        $rsInsert = $this->db->conn->Replace("SGD_RENV_REGENVIO",$record,'SGD_RENV_CODIGO', $autoquote = true);

    }

} /* end of class Envios */

?>

    
