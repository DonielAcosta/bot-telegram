<?php
/**
* ProteoERP
*
* @autor    Andres Hocevar
* @license  GNU GPL v3
*/
class Botpexclu extends Controller {
	var $mModulo = 'BOTPEXCLU';
	var $titp    = 'Palabras Excluidas';
	var $tits    = 'Palabras Excluidas';
	var $url     = 'bots/botpexclu/';

	function __construct(){
		parent::__construct();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		$this->datasis->modulo_nombre( 'BOTPEXCLU', $ventana=0, $this->titp  );
	}

	function index(){
		$this->instalar();
		$this->datasis->creaintramenu(array('modulo'=>'183','titulo'=>'Palabras Excluidas','mensaje'=>'Palabras Excluidas ','panel'=>'REDES SOCIALES','ejecutar'=>'bots/botpexclu/','target'=>'popu','visible'=>'S','pertenece'=>'1','ancho'=>900,'alto'=>600));
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
		$param['listados']    = $this->datasis->listados('BOTPEXCLU', 'JQ');
		$param['otros']       = $this->datasis->otros('BOTPEXCLU', 'JQ');
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

		$bodyscript .= $this->jqdatagrid->bsshow('botpexclu', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsadd( 'botpexclu', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsdel( 'botpexclu', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsedit('botpexclu', $ngrid, $this->url );

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

		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'frozen'        => 'true',
			'width'         => 40,
			'editable'      => 'false',
			'search'        => 'false'
		));


		$grid->addField('palabra');
		$grid->label('Palabra');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 250,
			'edittype'      => "'textarea'",
			'editoptions'   => "'{rows:2, cols:60}'",
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
		$grid->setAdd(    $this->datasis->sidapuede('BOTPEXCLU','INCLUIR%' ));
		$grid->setEdit(   $this->datasis->sidapuede('BOTPEXCLU','MODIFICA%'));
		$grid->setDelete( $this->datasis->sidapuede('BOTPEXCLU','BORR_REG%'));
		$grid->setSearch( $this->datasis->sidapuede('BOTPEXCLU','BUSQUEDA%'));
		$grid->setRowNum(30);
		$grid->setShrinkToFit('false');

		$grid->setBarOptions("addfunc: botpexcluadd, editfunc: botpexcluedit, delfunc: botpexcludel, viewfunc: botpexclushow");

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
		$mWHERE = $grid->geneTopWhere('botpexclu');

		$response   = $grid->getData('botpexclu', array(array()), array(), false, $mWHERE );
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
				$check = $this->datasis->dameval("SELECT count(*) FROM botpexclu WHERE $mcodp=".$this->db->escape($data[$mcodp]));
				if ( $check == 0 ){
					$this->db->insert('botpexclu', $data);
					echo "Registro Agregado";

					logusu('BOTPEXCLU',"Registro ????? INCLUIDO");
				} else
					echo "Ya existe un registro con ese $mcodp";
			} else
				echo "Fallo Agregado!!!";

		} elseif($oper == 'edit') {
			$nuevo  = $data[$mcodp];
			$anterior = $this->datasis->dameval("SELECT $mcodp FROM botpexclu WHERE id=$id");
			if ( $nuevo <> $anterior ){
				//si no son iguales borra el que existe y cambia
				$this->db->query("DELETE FROM botpexclu WHERE $mcodp=?", array($mcodp));
				$this->db->query("UPDATE botpexclu SET $mcodp=? WHERE $mcodp=?", array( $nuevo, $anterior ));
				$this->db->where("id", $id);
				$this->db->update("botpexclu", $data);
				logusu('BOTPEXCLU',"$mcodp Cambiado/Fusionado Nuevo:".$nuevo." Anterior: ".$anterior." MODIFICADO");
				echo "Grupo Cambiado/Fusionado en clientes";
			} else {
				unset($data[$mcodp]);
				$this->db->where("id", $id);
				$this->db->update('botpexclu', $data);
				logusu('BOTPEXCLU',"Grupo de Cliente  ".$nuevo." MODIFICADO");
				echo "$mcodp Modificado";
			}

		} elseif($oper == 'del') {
			$meco = $this->datasis->dameval("SELECT $mcodp FROM botpexclu WHERE id=$id");
			//$check =  $this->datasis->dameval("SELECT COUNT(*) FROM botpexclu WHERE id='$id' ");
			if ($check > 0){
				echo " El registro no puede ser eliminado; tiene movimiento ";
			} else {
				$this->db->query("DELETE FROM botpexclu WHERE id=$id ");
				logusu('BOTPEXCLU',"Registro ????? ELIMINADO");
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

		$edit = new DataEdit('', 'botpexclu');

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

		$edit->palabra = new textareaField('Palabra','palabra');
		$edit->palabra->rule='';
		$edit->palabra->cols = 70;
		$edit->palabra->rows = 4;

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
			//$data['content']  =  $this->load->view('view_botpexclu', $conten, false);
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
		if (!$this->db->table_exists('botpexclu')) {
			$mSQL="CREATE TABLE botpexclu (
			  id            INT(11)    NOT NULL AUTO_INCREMENT,
			  palabra       TEXT           NULL DEFAULT '',
			  PRIMARY KEY (id)
			) ENGINE=MyISAM";
			$this->db->query($mSQL);
		}
		//$campos=$this->db->list_fields('botpexclu');
		//if(!in_array('<#campo#>',$campos)){ }
	}
}

?>