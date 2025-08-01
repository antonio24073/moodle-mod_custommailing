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
 * Custommailing module upgrade code.
 *
 * @package    mod_custommailing
 * @author     jeanfrancois@cblue.be
 * @copyright  2021 CBlue SPRL
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * @param int $oldversion the version we are upgrading from
 * @return bool
 * @throws ddl_exception
 * @throws ddl_field_missing_exception
 * @throws ddl_table_missing_exception
 * @throws dml_exception
 * @throws downgrade_exception
 * @throws upgrade_exception
 */
function xmldb_custommailing_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2021033100) {

        $table = new xmldb_table('custommailing_mailing');
        $field = new xmldb_field('retroactive', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'mailingstatus');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Savepoint reached.
        upgrade_mod_savepoint(true, 2021033100, 'custommailing');
    }

    if ($oldversion < 2024020200) {

        $table = new xmldb_table('custommailing_mailing');
        $field = new xmldb_field('mailinggroups', XMLDB_TYPE_CHAR, '255', null, false, null, null, 'mailinglang');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Savepoint reached.
        upgrade_mod_savepoint(true, 2024020200, 'custommailing');
    }

    if ($oldversion < 2024070500) {

        // Define field mailingcohorts to be added to custommailing_mailing.
        $table = new xmldb_table('custommailing_mailing');

        $field = new xmldb_field('mailingcohorts', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'mailinggroups');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('groupcohortscombination', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'mailingcohorts');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_mod_savepoint(true, 2024070500, 'custommailing');
    }

     if ($oldversion < 2025072905) {

        // Define the table and new field.
        $table = new xmldb_table('custommailing_mailing');

        // Field: timesnumbermax
        $field = new xmldb_field('timesnumbermax', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 1);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define the table and new field.
        $table = new xmldb_table('custommailing_logs');

        // Field: timesnumbercount
        $field = new xmldb_field('timesnumbercount', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 1);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Upgrade savepoint.
        upgrade_mod_savepoint(true, 2025072905, 'custommailing');
    }


    return true;
}
