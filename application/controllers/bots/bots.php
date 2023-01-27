<?php
/**
* ProteoERP
*
* @autor    Andres Hocevar
* @license  GNU GPL v3
*/
class Bots extends Controller {
	var $mModulo = 'BOTS';
	var $titp    = 'BOTS PARA RRSS';
	var $tits    = 'BOTS PARA RRSS';
	var $url     = 'bots/bots/';

	function __construct(){
		parent::__construct();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		$this->datasis->modulo_nombre( 'BOTS', $ventana=0, $this->titp  );
	}

	function index(){
		$this->instalar();
		$this->datasis->creaintramenu(array('modulo'=>'177','titulo'=>'Bots de RRSS','mensaje'=>'Bots de redes sociales','panel'=>'REDES SOCIALES','ejecutar'=>'bots/bots','target'=>'popu','visible'=>'S','pertenece'=>'1','ancho'=>900,'alto'=>600));
		$this->datasis->modintramenu( 800, 600, substr($this->url,0,-1) );
		redirect($this->url.'jqdatag');
	}

	//******************************************************************
	// Layout en la Ventana
	//
	function jqdatag(){

		$grid = $this->defgrid();
		$param['grids'][] = $grid->deploy();

		//Funciones que ejecutan los botones
		$bodyscript = $this->bodyscript( $param['grids'][0]['gridname']);

		//Botones Panel Izq
		//$grid->wbotonadd(array("id"=>"funcion",   "img"=>"images/engrana.png",  "alt" => "Formato PDF", "label"=>"Ejemplo"));
		$WestPanel = $grid->deploywestp();

		$adic = array(
			array('id'=>'fedita',  'title'=>'Agregar/Editar Registro'),
			array('id'=>'fshow' ,  'title'=>'Mostrar Registro'),
			array('id'=>'fborra',  'title'=>'Eliminar Registro')
		);
		$SouthPanel = $grid->SouthPanel($this->datasis->traevalor('TITULO1'), $adic);

		$param['WestPanel']   = $WestPanel;
		//$param['EastPanel'] = $EastPanel;
		$param['SouthPanel']  = $SouthPanel;
		$param['listados']    = $this->datasis->listados('BOTS', 'JQ');
		$param['otros']       = $this->datasis->otros('BOTS', 'JQ');
		$param['temas']       = array('proteo','darkness','anexos1');
		$param['bodyscript']  = $bodyscript;
		$param['tabs']        = false;
		$param['encabeza']    = $this->titp;
		$param['tamano']      = $this->datasis->getintramenu( substr($this->url,0,-1) );
		$this->load->view('jqgrid/crud2',$param);
	}

	//******************************************************************
	// Funciones de los Botones
	//
	function bodyscript( $grid0 ){
		$bodyscript = '<script type="text/javascript">';
		$ngrid = '#newapi'.$grid0;

		$bodyscript .= $this->jqdatagrid->bsshow('bots', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsadd( 'bots', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsdel( 'bots', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsedit('bots', $ngrid, $this->url );

		//Wraper de javascript
		$bodyscript .= $this->jqdatagrid->bswrapper($ngrid);

		$bodyscript .= $this->jqdatagrid->bsfedita( $ngrid, '400', '400' );
		$bodyscript .= $this->jqdatagrid->bsfshow( '400', '400' );
		$bodyscript .= $this->jqdatagrid->bsfborra( $ngrid, '400', '400' );

		$bodyscript .= '});';

		$bodyscript .= '</script>';

		return $bodyscript;
	}

	//******************************************************************
	// Definicion del Grid o Tabla 
	//
	function defgrid( $deployed = false ){
		$i      = 1;
		$editar = "false";

		$grid  = new $this->jqdatagrid;

		// $grid->addField('Id');
		// $grid->label('Id');
		// $grid->params(array(
		// 	'search'        => 'true',
		// 	'editable'      => $editar,
		// 	'align'         => "'right'",
		// 	'edittype'      => "'text'",
		// 	'width'         => 40,
		// 	'editrules'     => '{ required:true }',
		// 	'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
		// 	'formatter'     => "'number'",
		// 	'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0 }'
		// ));

		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'frozen'        => 'true',
			'width'         => 40,
			'editable'      => 'false',
			'search'        => 'false'
		));


		$grid->addField('nombre');
		$grid->label('Nombre');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 100,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:40, maxlength: 40 }',
		));


		$grid->addField('token');
		$grid->label('Token');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 300,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:80, maxlength: 80 }',
		));


		$grid->addField('descripcion');
		$grid->label('Descripcion');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 100,
			'edittype'      => "'textarea'",
			'editoptions'   => "'{rows:2, cols:60}'",
		));


		$grid->addField('url');
		$grid->label('Url');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->showpager(true);
		$grid->setWidth('');
		$grid->setHeight('290');
		$grid->setTitle($this->titp);
		$grid->setfilterToolbar(true);
		$grid->setToolbar('false', '"top"');

		$grid->setFormOptionsE('closeAfterEdit:true, mtype: "POST", width: 520, height:300, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];},afterShowForm: function(frm){$("select").selectmenu({style:"popup"});} ');
		$grid->setFormOptionsA('closeAfterAdd:true,  mtype: "POST", width: 520, height:300, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];},afterShowForm: function(frm){$("select").selectmenu({style:"popup"});} ');
		$grid->setAfterSubmit("$('#respuesta').html('<span style=\'font-weight:bold; color:red;\'>'+a.responseText+'</span>'); return [true, a ];");

		$grid->setOndblClickRow('');		#show/hide navigations buttons
		$grid->setAdd(    $this->datasis->sidapuede('BOTS','INCLUIR%' ));
		$grid->setEdit(   $this->datasis->sidapuede('BOTS','MODIFICA%'));
		$grid->setDelete( $this->datasis->sidapuede('BOTS','BORR_REG%'));
		$grid->setSearch( $this->datasis->sidapuede('BOTS','BUSQUEDA%'));
		$grid->setRowNum(30);
		$grid->setShrinkToFit('false');

		$grid->setBarOptions("addfunc: botsadd, editfunc: botsedit, delfunc: botsdel, viewfunc: botsshow");

		#Set url
		$grid->setUrlput(site_url($this->url.'setdata/'));

		#GET url
		$grid->setUrlget(site_url($this->url.'getdata/'));

		if ($deployed) {
			return $grid->deploy();
		} else {
			return $grid;
		}
	}

	//******************************************************************
	// Busca la data en el Servidor por json
	//
	function getdata(){
		$grid       = $this->jqdatagrid;

		// CREA EL WHERE PARA LA BUSQUEDA EN EL ENCABEZADO
		$mWHERE = $grid->geneTopWhere('bots');

		$response   = $grid->getData('bots', array(array()), array(), false, $mWHERE );
		$rs = $grid->jsonresult( $response);
		echo $rs;
	}

	//******************************************************************
	// Guarda la Informacion del Grid o Tabla
	//
	function setData(){
		$this->load->library('jqdatagrid');
		$oper   = $this->input->post('oper');
		$id     = $this->input->post('id');
		$data   = $_POST;
		$mcodp  = "??????";
		$check  = 0;

		unset($data['oper']);
		unset($data['id']);
		if($oper == 'add'){
			if(false == empty($data)){
				$check = $this->datasis->dameval("SELECT count(*) FROM bots WHERE $mcodp=".$this->db->escape($data[$mcodp]));
				if ( $check == 0 ){
					$this->db->insert('bots', $data);
					echo "Registro Agregado";

					logusu('BOTS',"Registro ????? INCLUIDO");
				} else
					echo "Ya existe un registro con ese $mcodp";
			} else
				echo "Fallo Agregado!!!";

		} elseif($oper == 'edit') {
			$nuevo  = $data[$mcodp];
			$anterior = $this->datasis->dameval("SELECT $mcodp FROM bots WHERE id=$id");
			if ( $nuevo <> $anterior ){
				//si no son iguales borra el que existe y cambia
				$this->db->query("DELETE FROM bots WHERE $mcodp=?", array($mcodp));
				$this->db->query("UPDATE bots SET $mcodp=? WHERE $mcodp=?", array( $nuevo, $anterior ));
				$this->db->where("id", $id);
				$this->db->update("bots", $data);
				logusu('BOTS',"$mcodp Cambiado/Fusionado Nuevo:".$nuevo." Anterior: ".$anterior." MODIFICADO");
				echo "Grupo Cambiado/Fusionado en clientes";
			} else {
				unset($data[$mcodp]);
				$this->db->where("id", $id);
				$this->db->update('bots', $data);
				logusu('BOTS',"Grupo de Cliente  ".$nuevo." MODIFICADO");
				echo "$mcodp Modificado";
			}

		} elseif($oper == 'del') {
			$meco = $this->datasis->dameval("SELECT $mcodp FROM bots WHERE id=$id");
			//$check =  $this->datasis->dameval("SELECT COUNT(*) FROM bots WHERE id='$id' ");
			if ($check > 0){
				echo " El registro no puede ser eliminado; tiene movimiento ";
			} else {
				$this->db->query("DELETE FROM bots WHERE id=$id ");
				logusu('BOTS',"Registro ????? ELIMINADO");
				echo "Registro Eliminado";
			}
		};
	}


	//******************************************************************
	// Edicion 

	function dataedit(){
		$this->rapyd->load('dataedit');
		$script= '
		$(function() {
			$("#fecha").datepicker({dateFormat:"dd/mm/yy"});
			$(".inputnum").numeric(".");
		});
		';

		$edit = new DataEdit('', 'bots');

		$edit->script($script,'modify');
		$edit->script($script,'create');
		$edit->on_save_redirect=false;

		$edit->back_url = site_url($this->url.'filteredgrid');

		$edit->post_process('insert','_post_insert');
		$edit->post_process('update','_post_update');
		$edit->post_process('delete','_post_delete');
		$edit->pre_process('insert', '_pre_insert' );
		$edit->pre_process('update', '_pre_update' );
		$edit->pre_process('delete', '_pre_delete' );

		// $edit->Id = new inputField('Id','Id');
		// // $edit->Id->rule='integer';
		// $edit->Id->css_class='inputonlynum';
		// $edit->Id->size =13;
		// $edit->Id->maxlength =11;

		$edit->nombre = new inputField('Nombre','nombre');
		$edit->nombre->rule='';
		$edit->nombre->size =42;
		$edit->nombre->maxlength =40;

		$edit->token = new inputField('Token','token');
		$edit->token->rule='';
		$edit->token->size =42;
		$edit->token->maxlength =80;

		$edit->descripcion = new textareaField('Descripcion','descripcion');
		$edit->descripcion->rule='';
		$edit->descripcion->cols = 42;
		$edit->descripcion->rows = 4;

		$edit->url = new inputField('Url','url');
		$edit->url->rule='';
		$edit->url->size =42;
		$edit->url->maxlength =100;
		
		$edit->comandos = new textareaField('Comandos','comandos');
		$edit->comandos->rule='';
		$edit->comandos->cols = 42;
		$edit->comandos->rows = 4;
		$edit->build();

		if($edit->on_success()){
			$rt=array(
				'status' =>'A',
				'mensaje'=>'Registro guardado',
				'pk'     =>$edit->_dataobject->pk
			);
			echo json_encode($rt);
		}else{
			echo $edit->output;
			//$conten['form']  =&  $edit;
			//$data['content']  =  $this->load->view('view_bots', $conten, false);
		}
	}

	function _pre_insert($do){
		$do->error_message_ar['pre_ins']='';
		return true;
	}

	function _pre_update($do){
		$do->error_message_ar['pre_upd']='';
		return true;
	}

	function _pre_delete($do){
		$do->error_message_ar['pre_del']='';
		return true;
	}

	function _post_insert($do){
		$primary =implode(',',$do->pk);
		logusu($do->table,"Creo $this->tits $primary ");
	}

	function _post_update($do){
		$primary =implode(',',$do->pk);
		logusu($do->table,"Modifico $this->tits $primary ");
	}

	function _post_delete($do){
		$primary =implode(',',$do->pk);
		logusu($do->table,"Elimino $this->tits $primary ");
	}


	function instalar(){

		
		if (!$this->db->table_exists('bots')) {
			$mSQL="CREATE TABLE `bots` (
			  id            INT(10)    NOT NULL AUTO_INCREMENT,
			  nombre        VARCHAR(50)    NULL DEFAULT '',
			  token         VARCHAR(80)    NULL DEFAULT '',
			  descripcion   TEXT           NULL DEFAULT '',
			  url           VARCHAR(100)   NULL DEFAULT '',
			  PRIMARY KEY (id)
			) ENGINE=MyISAM";
	  		$this->db->query($mSQL);
		}
		$campos = $this->db->list_fields('bots');
		if(!in_array('nombre', $campos)) {
			$this->db->query("ALTER TABLE bots ADD COLUMN nombre VARCHAR(50) NULL DEFAULT NULL");
		}
		$campos = $this->db->list_fields('bots');
		if(!in_array('token', $campos)) {
			$this->db->query("ALTER TABLE bots ADD COLUMN token VARCHAR(50) NULL DEFAULT NULL");
		}
		if(!in_array('descripcion', $campos)) {
			$this->db->query("ALTER TABLE bots ADD COLUMN descripcion TEXT(200) NULL DEFAULT NULL");
		}
		if(!in_array('url', $campos)) {
			$this->db->query("ALTER TABLE bots ADD COLUMN url VARCHAR(50) NULL DEFAULT NULL");
		}
	
		// $a = 'DROP TABLE bots';
		// $this->db->query($a);
		//$campos=$this->db->list_fields('bots');
		//if(!in_array('<#campo#>',$campos)){ }
	}
}

?>