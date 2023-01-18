<?php
/**
* ProteoERP
*
* @autor    Andres Hocevar
* @license  GNU GPL v3
*/
echo $form_scripts;
echo $form_begin;

if(isset($form->error_string)) echo '<div class="alert">'.$form->error_string.'</div>';
if($form->_status <> 'show'){ ?>

<script language="javascript" type="text/javascript">
</script>
<?php } ?>

<fieldset  style='border: 1px outset #FEB404;background: #FFFCE8;'>
<table width='100%'>
	<tr>
		<td class="littletablerowth"><?php echo $form->id->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->id->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->nombres->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->nombres->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->apellidos->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->apellidos->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->cedula->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->cedula->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->rif->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->rif->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->ano_g->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->ano_g->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->fecha->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->fecha->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->cpde->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->cpde->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->finicial->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->finicial->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->carga_f->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->carga_f->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->idpecdnm25->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->idpecdnm25->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->pshcm->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->pshcm->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->smoh->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->smoh->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->iavppavapa->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->iavppavapa->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->total_g->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->total_g->output; ?></td>
	</tr>
</table>
</fieldset>
<?php echo $form_end; ?>
