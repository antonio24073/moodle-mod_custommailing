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
 * This file manages the public functions of this module
 *
 * @package    mod_custommailing
 * @author     jeanfrancois@cblue.be,olivier@cblue.be
 * @copyright  2021 CBlue SPRL
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once $CFG->libdir . '/formslib.php';
require_once $CFG->dirroot . '/lib/grouplib.php';
require_once $CFG->dirroot . '/cohort/lib.php';
/**
 * Class mailing_form
 */
class mailing_form extends moodleform
{

    /**
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function definition()
    {
        global $COURSE, $PAGE;

        $cert = false;
        $mform =& $this->_form;
        $course_module_context = $PAGE->context;
        $custom_cert = core_plugin_manager::instance()->get_plugin_info('mod_customcert');
        $courseid = $COURSE->id;
        if (!empty($this->_customdata['mailingid'])) {
            $mailing = \mod_custommailing\Mailing::get($this->_customdata['mailingid']);
        }

        $days = [];
        for ($i = 1; $i <= 60; $i++) {
            $days[$i] = $i;
        }

        $hours = [];
        for ($i = 0; $i < 24; $i++) {
            if ($i < 10) {
                $hours[$i] = "0$i";
            } else {
                $hours[$i] = $i;
            }
        }

        $minutes = [];
        for ($i = 0; $i < 60; $i += 5) {
            if ($i < 10) {
                $minutes[$i] = "0$i";
            } else {
                $minutes[$i] = $i;
            }
        }

        $scorm = custommailing_get_activities('scorm');
        if ($custom_cert) {
            $cert = custommailing_get_activities('customcert');
        }

        $source = [];
        $source[0] = get_string('select', 'mod_custommailing');
        if ($scorm) {
            $source[MAILING_SOURCE_MODULE] = get_string('module', 'mod_custommailing');
        }
        $source[MAILING_SOURCE_COURSE] = get_string('course', 'mod_custommailing');
        if ($cert) {
            $source[MAILING_SOURCE_CERT] = get_string('certificate', 'mod_custommailing');
        }

        // Add id
        $mform->addElement('hidden', 'id', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $course_module_context->instanceid);

        if (!empty($this->_customdata['mailingid'])) {
            // Add mailingid
            $mform->addElement('hidden', 'mailingid', 'mailingid');
            $mform->setType('mailingid', PARAM_INT);
            $mform->setDefault('mailingid', $this->_customdata['mailingid']);
        }

        // Add name
        $mform->addElement('text', 'mailingname', get_string('mailingname', 'mod_custommailing'), 'maxlength="255" size="32"');
        $mform->setType('mailingname', PARAM_RAW_TRIMMED);
        $mform->addRule('mailingname', get_string('required'), 'required');

        // Todo v2 : Add lang
//        $mform->addElement('select', 'mailinglang', get_string('mailinglang', 'mod_custommailing'), get_string_manager()->get_list_of_translations());
//        $mform->setType('mailinglang', PARAM_LANG);
//        $mform->addRule('mailinglang', get_string('required'), 'required');

        // Select Source
        $mform->addElement('select', 'source', get_string('selectsource', 'mod_custommailing'), $source);
        $mform->setType('source', PARAM_INT);
        if (!empty($this->_customdata['mailingid'])) {
            $mform->disabledIf('source', 'mailingid', 'noteq', 0);
        }

        // Add target activity
        $mform->addElement('select', 'targetmoduleid', get_string('targetmoduleid', 'mod_custommailing'), $scorm);
        $mform->setType('targetmoduleid', PARAM_INT);
        $mform->hideIf('targetmoduleid', 'source', 'noteq', 1);

        // Add custom cert
        if ($cert) {
            $mform->addElement('select', 'customcert', get_string('certificate', 'mod_custommailing'), custommailing_getcustomcertsfromcourse($courseid));
            $mform->setType('customcert', PARAM_INT);
            $mform->hideIf('customcert', 'source', 'noteq', 3);
            $mform->addHelpButton('customcert', 'customcert', 'mod_custommailing');
            if (!empty($mailing->customcertmoduleid)) {
                $mform->setDefault('customcert', $mailing->customcertmoduleid);
            }
        }

        // Add mode
        $mailing_mode_module = [];

        $mailing_mode_module[] =& $mform->createElement('radio', 'mailingmodemodule', null, '', 'option');
        $mailing_mode_module[] =& $mform->createElement('select', 'mailingdelaymodule', null, $days);
        $mailing_mode_module[] =& $mform->createElement(
            'select', 'mailingmodemoduleoption', null, [
                MAILING_MODE_DAYSFROMFIRSTLAUNCH => get_string('firstlaunch', 'mod_custommailing'),
                MAILING_MODE_DAYSFROMLASTLAUNCH => get_string('lastlaunch', 'mod_custommailing'),
            ]
        );
        $mailing_mode_module[] =& $mform->createElement('checkbox', 'mailingmodecompletion', get_string('andtargetactivitynotcompleted', 'mod_custommailing'));
        $mform->addGroup($mailing_mode_module, 'mailingmodemodulegroup', get_string('sendmailing', 'mod_custommailing'), ' ', false);
        $mform->setType('mailingmodemodule', PARAM_INT);
        $mform->setDefault('mailingmodemodule', 0);
        $mform->hideIf('mailingmodemodule', 'source', 'noteq', 1);
        $mform->hideIf('mailingdelaymodule', 'source', 'noteq', 1);
        $mform->hideIf('mailingmodemoduleoption', 'source', 'noteq', 1);
        $mform->hideIf('mailingmodemodulegroup', 'source', 'noteq', 1);
        if (!empty($source[MAILING_SOURCE_COURSE]) && !empty($mailing->mailingmode)) {
            $mform->setDefault('mailingmodemoduleoption', $mailing->mailingmode);
            if (!empty($mailing->mailingdelay)) {
                $mform->setDefault('mailingdelaymodule', $mailing->mailingdelay);
            }
        }

        $mailing_mode[] =& $mform->createElement('radio', 'mailingmode', null, '', 'option');
        $mailing_mode[] =& $mform->createElement('select', 'mailingdelay', null, $days);
        $mailing_mode[] =& $mform->createElement(
            'select', 'mailingmodeoption', null, [
                MAILING_MODE_DAYSFROMINSCRIPTIONDATE => get_string('courseenroldate', 'mod_custommailing'),
                MAILING_MODE_DAYSFROMLASTCONNECTION => get_string('courselastaccess', 'mod_custommailing'),
            ]
        );

        $mailing_mode[] =& $mform->createElement('static', 'timesnumbermax_label', '', ' - ' . get_string('mailingtimesnumbermax', 'mod_custommailing'));
        $mailing_mode[] =& $mform->createElement('select', 'timesnumbermax', '', $days);

        $mform->addGroup($mailing_mode, 'mailingmodegroup', get_string('sendmailing', 'mod_custommailing'), ' ', false);
        
        // $mform->addElement('radio', 'mailingmode', null, get_string('atcourseenrol', 'mod_custommailing'), MAILING_MODE_REGISTRATION);
        $mailing_mode_atcourseenrol = [];
        $mailing_mode_atcourseenrol[] =& $mform->createElement('radio', 'mailingmode', null,  get_string('atcourseenrol', 'mod_custommailing'), MAILING_MODE_REGISTRATION);
        $mailing_mode_atcourseenrol[] =& $mform->createElement('static', 'mailingdelay_atcourseenrol_label', '', ' - ' . get_string('atcourseenrol_delayminutes', 'mod_custommailing'));
        $mailing_mode_atcourseenrol[] =& $mform->createElement('select', 'mailingdelay_atcourseenrol', null, $days);
        $mform->addGroup($mailing_mode_atcourseenrol, 'mailingmodegroup_atcourseenrol', '', ' ', false);
        

        $mform->setType('mailingmode', PARAM_RAW);
        $mform->setDefault('mailingmode', 0);
        $mform->hideIf('mailingmode', 'source', 'noteq', 2);
        $mform->hideIf('mailingdelay', 'source', 'noteq', 2);
        $mform->hideIf('mailingmodeoption', 'source', 'noteq', 2);
        $mform->hideIf('mailingmodegroup', 'source', 'noteq', 2);
        $mform->hideIf('mailingmodegroup_atcourseenrol', 'source', 'noteq', 2);
        if (!empty($mailing->targetmodulestatus)) {
            $mform->setDefault('mailingmodecompletion', $mailing->targetmodulestatus);
            $mform->setDefault('mailingmode', $mailing->mailingmode);
            $mform->setDefault('mailingmodeoption', $mailing->mailingmode);
            if (!empty($mailing->mailingdelay)) {
                $mform->setDefault('mailingdelay', $mailing->mailingdelay);
            }
        }
                $mform->setDefault('mailingdelay_atcourseenrol', $mailing->mailingdelay);

        // Add retroactive mode
        $mform->addElement('selectyesno', 'retroactive', get_string('retroactive', 'mod_custommailing'));
        $mform->setType('retroactive', PARAM_BOOL);
        $mform->setDefault('retroactive', 0);
        $mform->addHelpButton('retroactive', 'retroactive', 'mod_custommailing');
        if (!empty($this->_customdata['mailingid'])) {
            $mform->disabledIf('retroactive', 'mailingid', 'noteq', 0);
        }

        // Add groups
        if($groups = groups_get_all_groups($courseid)) {
            foreach($groups as $key => $group) {
                $groups[$key] = format_string($group->name, true, ['context' => $course_module_context]);
            }
        }
        if(!empty($groups)) {
            $input = $mform->addElement('searchableselector', 'mailinggroups', get_string('mailinggroups', 'mod_custommailing'), $groups);
            $input->setMultiple(true);
            $input->setSize(count($groups) > 5 ? 10 : 5);
            $mform->setDefault('mailinggroups', !empty($mailing->mailinggroups) ? $mailing->mailinggroups : []);
            $mform->addHelpButton('mailinggroups', 'mailinggroups', 'mod_custommailing');
        }

        $cohorts = cohort_get_all_cohorts(0, 0);
        $cohorts_name_by_ids = [];
        foreach ($cohorts['cohorts'] as $cohort) {
            $cohorts_name_by_ids[$cohort->id] = $cohort->name;
        }

        $input = $mform->addElement('searchableselector', 'mailingcohorts', get_string('mailingcohorts', 'mod_custommailing'), $cohorts_name_by_ids);
        $input->setMultiple(true);
//        $input->setSize(count($cohorts) > 5 ? 10 : 5);
            $mform->setDefault('mailingcohorts', !empty($mailing->mailinggroups) ? $mailing->mailinggroups : []);
        $mform->addHelpButton('mailingcohorts', 'mailingcohorts', 'mod_custommailing');

        $options = [
            '0' => get_string('usermemberofselectedgroupsorselectedcohorts', 'mod_custommailing'),
            '1' => get_string('usermemberofselectedgroupsandselectedcohorts', 'mod_custommailing'),
        ];
        $mform->addElement('select', 'groupcohortscombination', get_string('groupcohortscombination', 'mod_custommailing'), $options);

        // Add subject
        $mform->addElement('text', 'mailingsubject', get_string('mailingsubject', 'mod_custommailing'));
        $mform->setType('mailingsubject', PARAM_RAW_TRIMMED);
        $mform->addRule('mailingsubject', get_string('required'), 'required');

        // Add body
        $mform->addElement('editor', 'mailingcontent', get_string('mailingcontent', 'mod_custommailing'), '', ['enable_filemanagement' => false]);
        $mform->setType('mailingcontent', PARAM_RAW);
        $mform->addRule('mailingcontent', get_string('required'), 'required');
        $mform->addHelpButton('mailingcontent', 'mailingcontent', 'mod_custommailing');

        //Todo v2 : starttime
//        $start_time = [];
//        $start_time[] =& $mform->createElement('select', 'starttimehour', '', $hours);
//        $start_time[] =& $mform->createElement('static', '', null, '&nbsp:&nbsp;');
//        $start_time[] =& $mform->createElement('select', 'starttimeminute', '', $minutes);
//        $mform->addGroup($start_time, 'starttime', get_string('starttime', 'mod_custommailing'), ' ', false);
//        $mform->addRule('starttime', get_string('required'), 'required');

        // Add status
        $mform->addElement('selectyesno', 'mailingstatus', get_string('enabled', 'mod_custommailing'));
        $mform->setType('mailingstatus', PARAM_BOOL);
        $mform->setDefault('mailingstatus', 1);

        // Add standard buttons, common to all modules.
        if (!empty($this->_customdata['mailingid'])) {
            $submitlabel = get_string('updatemailing', 'mod_custommailing');
        } else {
            $submitlabel = get_string('createmailing', 'mod_custommailing');
        }
        $this->add_action_buttons(true, $submitlabel);
    }

    /**
     * @param array $data
     * @param array $files
     * @return array
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);

        return $errors;
    }
}
