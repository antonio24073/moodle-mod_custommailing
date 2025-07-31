<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file manages all the en strings
 *
 * @package    mod_custommailing
 * @author     jeanfrancois@cblue.be
 * @copyright  2021 CBlue SPRL
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Envio Personalizado';

$string['andtargetactivitynotcompleted'] = 'e o módulo de destino não foi concluído pelo usuário';
$string['atactivitycompleted'] = "Ao concluir o módulo de destino";
$string['atcourseenrol'] = "Ao se inscrever no curso";
$string['atfirstlaunch'] = "No primeiro acesso (módulo de destino)";
$string['certificate'] = "Certificado";
$string['confirmdelete'] = 'Tem certeza que deseja excluir este envio: {$a}';
$string['course'] = "Curso";
$string['coursecompletionenabled'] = "Aviso: a conclusão do curso foi ativada.";
$string['coursecompletionnotenabled'] = "Erro: a atividade foi adicionada, mas a conclusão do curso não pôde ser ativada.";
$string['courseenroldate'] = "dia(s) após a inscrição no curso";
$string['courselastaccess'] = "dia(s) após o último acesso ao curso";
$string['createmailing'] = "Criar envio";
$string['createnewmailing'] = 'Criar um novo envio';
$string['crontask'] = "Tarefa Cron";
$string['customcert'] = "Certificado";
$string['customcert_help'] = "Um e-mail com certificado em anexo será enviado a cada usuário que cumprir todos os requisitos do certificado";
$string['custommailingname'] = "Nome";
$string['daysafter'] = 'dia(s) após:';
$string['debugmode'] = "modo de depuração";
$string['debugmode_help'] = "atraso do envio em minutos ao invés de dias";
$string['disabled'] = "Desativado";
$string['enabled'] = "Ativado";
$string['firstlaunch'] = "dia(s) após o primeiro acesso (módulo de destino)";
$string['lastlaunch'] = "dia(s) após o último acesso (módulo de destino)";
$string['log_mailing_failed'] = 'Falhou';
$string['log_mailing_idle'] = 'Inativo';
$string['log_mailing_processing'] = 'Processando';
$string['log_mailing_sent'] = 'Enviado';
$string['log_mailing_unknown'] = 'Desconhecido';
$string['logtable'] = "Tabela de log";
$string['mailingadded'] = "Envio adicionado";
$string['mailingcontent'] = "Corpo";
$string['mailingcontent_help'] = 'Você pode usar as seguintes variáveis no e-mail:
<ul>
<li>%firstname%</li>
<li>%lastname%</li>
</ul>';
$string['mailingdeleted'] = 'Envio excluído';
$string['mailinglang'] = 'Idioma';
$string['mailingname'] = 'Nome';
$string['mailinggroups'] = 'Grupos';
$string['mailingmailingcohorts'] = 'Coortes';
$string['mailinggroups_help'] = 'Selecione os grupos desejados. Se nenhum for selecionado, o e-mail será enviado para todos os grupos.';
$string['mailingcohorts'] = 'Coortes';
$string['mailingcohorts_help'] = 'Selecione as coortes desejadas. Se nenhuma for selecionada, o e-mail será enviado para todas as coortes.';
$string['groupcohortscombination'] = 'Combinação Grupos/Coortes';
$string['groupcohortscombination_help'] = 'Se você selecionar tanto grupos quanto coortes, o e-mail será enviado para usuários que pertençam aos grupos selecionados, às coortes selecionadas ou a ambos. Escolha a opção apropriada para especificar como combinar essas seleções.';
$string['usermemberofselectedgroupsorselectedcohorts'] = 'Usuário pertencente a um grupo OU a uma coorte selecionada';
$string['usermemberofselectedgroupsandselectedcohorts'] = 'Usuário pertencente a um grupo E a uma coorte selecionada';
$string['mailingsubject'] = "Assunto";
$string['mailingtargetactivitystatuscomplete'] = "Módulo de destino completo";
$string['mailingtargetactivitystatusincomplete'] = "Módulo de destino incompleto";
$string['mailingupdated'] = "Envio atualizado";
$string['module'] = "Scorm";
$string['modulename'] = 'Envio Personalizado';
$string['modulenameplural'] = "Envios Personalizados";
$string['pluginadministration'] = 'Administração do envio personalizado';
$string['retroactive'] = "Retroativo";
$string['retroactive_help'] = "Efeito retroativo das condições de envio";
$string['select'] = "Selecionar";
$string['selectsource'] = "Fonte";
$string['sendmailing'] = "Enviar envio";
$string['settings'] = "Configurações";
$string['starttime'] = "Horário de envio";
$string['targetactivitynotfound'] = "Módulo de destino não encontrado";
$string['targetmoduleid'] = 'Módulo de destino';
$string['timecreated'] = 'Data de criação';
$string['timemodified'] = 'Data de modificação';
$string['updatemailing'] = "Atualizar envio";

$string['privacy:metadata'] = 'O plugin Envio Personalizado armazena registros pessoais de cada e-mail enviado por cada envio.';
$string['privacy:metadata:custommailing_logs'] = 'Registros do Envio Personalizado';
$string['privacy:metadata:custommailingmailingid'] = 'ID do envio';
$string['privacy:metadata:emailtouserid'] = 'ID do usuário';
$string['privacy:metadata:emailstatus'] = 'Status do e-mail';
$string['privacy:metadata:timecreated'] = 'Data de criação';
$string['privacy:metadata:timemodified'] = 'Data de modificação';

$string['atcourseenrol_delayminutes'] = "Atraso em minutos: ";
$string['mailingtimesnumbermax'] = "Número de vezes: ";
