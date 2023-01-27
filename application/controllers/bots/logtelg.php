<?php
/**
* ProteoERP
*
* @autor    Andres Hocevar
* @license  GNU GPL v3
*/
class Logtelg extends Controller {
	var $mModulo = 'LOGTELG';
	var $titp    = 'Modulo LOGTELG';
	var $tits    = 'Modulo LOGTELG';
	var $url     = 'bots/logtelg/';

	function __construct(){
		parent::__construct();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		$this->datasis->modulo_nombre( 'LOGTELG', $ventana=0, $this->titp  );
	}

	function index(){
		$this->instalar();
		$this->datasis->creaintramenu(array('modulo'=>'176','titulo'=>'Data Bot','mensaje'=>'Data Bot','panel'=>'REDES SOCIALES','ejecutar'=>'bots/logtelg/','target'=>'popu','visible'=>'S','pertenece'=>'1','ancho'=>900,'alto'=>600));
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
		$param['listados']    = $this->datasis->listados('LOGTELG', 'JQ');
		$param['otros']       = $this->datasis->otros('LOGTELG', 'JQ');
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

		$bodyscript .= $this->jqdatagrid->bsshow('logtelg', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsadd( 'logtelg', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsdel( 'logtelg', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsedit('logtelg', $ngrid, $this->url );

		//Wraper de javascript
		$bodyscript .= $this->jqdatagrid->bswrapper($ngrid);

		$bodyscript .= $this->jqdatagrid->bsfedita( $ngrid, '300', '400' );
		$bodyscript .= $this->jqdatagrid->bsfshow( '300', '400' );
		$bodyscript .= $this->jqdatagrid->bsfborra( $ngrid, '300', '400' );

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

		// $grid->addField('id');
		// $grid->label('Id');
		// $grid->params(array(
		// 	'align'         => "'center'",
		// 	'frozen'        => 'true',
		// 	'width'         => 40,
		// 	'editable'      => 'false',
		// 	'search'        => 'false'
		// ));


		$grid->addField('first_name');
		$grid->label('First_name');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 100,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('last_name');
		$grid->label('Last_name');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 100,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('username');
		$grid->label('Username');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 130,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('text');
		$grid->label('Text');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 150,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('fecha');
		$grid->label('Fecha');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 110,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('message_id');
		$grid->label('Message_id');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 70,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{size:50, maxlength: 50 }',
		));


		$grid->addField('id_bot');
		$grid->label('Id_bot');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 100,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('is_bot');
		$grid->label('Is_bot');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 50,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));


		$grid->addField('language_code');
		$grid->label('Language_code');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 85,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('type');
		$grid->label('Type');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));


		$grid->addField('xoffset');
		$grid->label('Xoffset');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 50,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('length');
		$grid->label('Length');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 50,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('entities_type');
		$grid->label('Entities_type');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 90,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('respuesta');
		$grid->label('Respuesta');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 130,
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
		$grid->setAdd(    $this->datasis->sidapuede('LOGTELG','INCLUIR%' ));
		$grid->setEdit(   $this->datasis->sidapuede('LOGTELG','MODIFICA%'));
		$grid->setDelete( $this->datasis->sidapuede('LOGTELG','BORR_REG%'));
		$grid->setSearch( $this->datasis->sidapuede('LOGTELG','BUSQUEDA%'));
		$grid->setRowNum(30);
		$grid->setShrinkToFit('false');

		$grid->setBarOptions("addfunc: logtelgadd, editfunc: logtelgedit, delfunc: logtelgdel, viewfunc: logtelgshow");

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
		$mWHERE = $grid->geneTopWhere('logtelg');

		$response   = $grid->getData('logtelg', array(array()), array(), false, $mWHERE, 'id', 'desc' );
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
				$check = $this->datasis->dameval("SELECT count(*) FROM logtelg WHERE $mcodp=".$this->db->escape($data[$mcodp]));
				if ( $check == 0 ){
					$this->db->insert('logtelg', $data);
					echo "Registro Agregado";

					logusu('LOGTELG',"Registro ????? INCLUIDO");
				} else
					echo "Ya existe un registro con ese $mcodp";
			} else
				echo "Fallo Agregado!!!";

		} elseif($oper == 'edit') {
			$nuevo  = $data[$mcodp];
			$anterior = $this->datasis->dameval("SELECT $mcodp FROM logtelg WHERE id=$id");
			if ( $nuevo <> $anterior ){
				//si no son iguales borra el que existe y cambia
				$this->db->query("DELETE FROM logtelg WHERE $mcodp=?", array($mcodp));
				$this->db->query("UPDATE logtelg SET $mcodp=? WHERE $mcodp=?", array( $nuevo, $anterior ));
				$this->db->where("id", $id);
				$this->db->update("logtelg", $data);
				logusu('LOGTELG',"$mcodp Cambiado/Fusionado Nuevo:".$nuevo." Anterior: ".$anterior." MODIFICADO");
				echo "Grupo Cambiado/Fusionado en clientes";
			} else {
				unset($data[$mcodp]);
				$this->db->where("id", $id);
				$this->db->update('logtelg', $data);
				logusu('LOGTELG',"Grupo de Cliente  ".$nuevo." MODIFICADO");
				echo "$mcodp Modificado";
			}

		} elseif($oper == 'del') {
			$meco = $this->datasis->dameval("SELECT $mcodp FROM logtelg WHERE id=$id");
			//$check =  $this->datasis->dameval("SELECT COUNT(*) FROM logtelg WHERE id='$id' ");
			if ($check > 0){
				echo " El registro no puede ser eliminado; tiene movimiento ";
			} else {
				$this->db->query("DELETE FROM logtelg WHERE id=$id ");
				logusu('LOGTELG',"Registro ????? ELIMINADO");
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

		$edit = new DataEdit('', 'logtelg');

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

		$edit->first_name = new inputField('First_name','first_name');
		$edit->first_name->rule='';
		$edit->first_name->size =17;
		$edit->first_name->maxlength =15;

		$edit->last_name = new inputField('Last_name','last_name');
		$edit->last_name->rule='';
		$edit->last_name->size =17;
		$edit->last_name->maxlength =15;

		$edit->username = new inputField('Username','username');
		$edit->username->rule='';
		$edit->username->size =17;
		$edit->username->maxlength =15;

		$edit->text = new inputField('Text','text');
		$edit->text->rule='';
		$edit->text->size =17;
		$edit->text->maxlength =15;

		$edit->fecha = new inputField('Fecha','fecha');
		$edit->fecha->rule='';
		$edit->fecha->size =17;
		$edit->fecha->maxlength =15;

		$edit->message_id = new inputField('Message_id','message_id');
		$edit->message_id->rule='';
		$edit->message_id->size =17;
		$edit->message_id->maxlength =15;

		$edit->id_bot = new inputField('Id_bot','id_bot');
		$edit->id_bot->rule='';
		$edit->id_bot->size =17;
		$edit->id_bot->maxlength =15;

		$edit->is_bot = new inputField('Is_bot','is_bot');
		$edit->is_bot->rule='';
		$edit->is_bot->size =17;
		$edit->is_bot->maxlength =15;


		$edit->language_code = new inputField('Language_code','language_code');
		$edit->language_code->rule='';
		$edit->language_code->size =17;
		$edit->language_code->maxlength =15;

		$edit->type = new inputField('Type','type');
		$edit->type->rule='';
		$edit->type->size =17;
		$edit->type->maxlength =15;

		$edit->xoffset = new inputField('Xoffset','xoffset');
		$edit->xoffset->rule='';
		$edit->xoffset->size =17;
		$edit->xoffset->maxlength =15;

		$edit->length = new inputField('Length','length');
		$edit->length->rule='';
		$edit->length->size =17;
		$edit->length->maxlength =15;

		$edit->entities_type = new inputField('Entities_type','entities_type');
		$edit->entities_type->rule='';
		$edit->entities_type->size =17;
		$edit->entities_type->maxlength =15;

		$edit->respuesta = new inputField('Respuesta','respuesta');
		$edit->respuesta->rule='';
		$edit->respuesta->size =30;
		$edit->respuesta->maxlength =30;

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
			//$data['content']  =  $this->load->view('view_logtelg', $conten, false);
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
		if (!$this->db->table_exists('logtelg')) {
			$mSQL="CREATE TABLE logtelg (
			  id            INT(10)    NOT NULL AUTO_INCREMENT,
			  first_name    VARCHAR(200)   NULL DEFAULT '',
			  last_name     VARCHAR(200)   NULL DEFAULT '',
			  username      VARCHAR(200)   NULL DEFAULT '',
			  text          TEXT           NULL DEFAULT '',
			  fecha         DATETIME       NULL DEFAULT 0,
			  message_id    INT(11)        NULL DEFAULT 0,
			  id_bot        INT(11)        NULL DEFAULT 0,
			  is_bot        INT            NULL DEFAULT 0,
			  language_code VARCHAR(3)     NULL DEFAULT '',
			  type          varchar(10)    NULL DEFAULT '',
			  xoffset       VARCHAR(10)    NULL DEFAULT '',
			  length        VARCHAR(100)   NULL DEFAULT '',
			  entities_type VARCHAR(100)   NULL DEFAULT '',
			  respuesta     TEXT           NULL DEFAULT '',
			  PRIMARY KEY (id)
			) ENGINE=MyISAM";
			$this->db->query($mSQL);
		}
		
		//$campos=$this->db->list_fields('logtelg');
		//if(!in_array('<#campo#>',$campos)){ }
	}
}

?>