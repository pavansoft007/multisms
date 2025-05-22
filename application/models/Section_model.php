<?php
// Section_model for handling section lookups
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Section_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get section by name and class ID
     * @param string $section_name
     * @param int|null $class_id
     * @return array|null
     */
    public function get_section_by_name($section_name, $class_id = null) {
        $this->db->where('name', $section_name);
        if ($class_id !== null) {
            $this->db->where('class_id', $class_id);
        }
        $query = $this->db->get('section');
        return $query->row_array();
    }
}
