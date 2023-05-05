public function create()

    {

        $data['date'] = $this->model->list();

        $data['title'] = display('add');

        #-------------------------------#




        if ($this->input->get_post('block_time_for') != null) {




            $this->form_validation->set_rules('block_time_for', "Block Time For", 'required');




            $this->form_validation->set_rules('reason', "reason", 'required');

        } else if ($this->input->get_post('doctor_id', true) != null) {

            $this->form_validation->set_rules('doctor_id', display('doctor_name'), 'required|max_length[11]');

            $this->form_validation->set_rules('patent_id', "Patient", 'required|max_length[11]');

            $this->form_validation->set_rules('duration', "Duration", 'required');

            $this->form_validation->set_rules('appointment_type', "Appointment Type", 'required');

            $this->form_validation->set_rules('when, "WHENS", 'required');

            $this->form_validation->set_rules('afrom_time', "FROM TIME", 'required');

            $this->form_validation->set_rules('ato_time', "TO TIME", 'required');

        } else {

            $this->form_validation->set_rules('patent_id', "Patient", 'required|max_length[11]');

        }




        $whens = '';

        $from_time = '';

        $to_time = '';

        $type = '';

        $repeatdate = '';

        $reason = '';

        $repeat = '';

        if ($this->input->post('repeat_date', true) != '') {

            $repeatdate = date('Y-m-d', strtotime($this->input->post('repeat_date', true)));

        } else {

            $repeatdate = '0000-00-00';

        }

        if ($this->input->post('awhens', true)) {

            $whens = date('Y-m-d', strtotime($this->input->post('awhens', true)));

        } else if ($this->input->post('bwhens', true)) {

            $whens = date('Y-m-d', strtotime($this->input->post('bwhens', true)));

        }

        if ($this->input->post('afrom_time', true)) {

            $from_time = date('H:i:s', strtotime($this->input->post('afrom_time', true)));

        } else if ($this->input->post('bfrom_time', true)) {

            $from_time = date('H:i:s', strtotime($this->input->post('bfrom_time', true)));

        }




        if ($this->input->post('ato_time', true)) {

            $to_time = date('H:i:s', strtotime($this->input->post('ato_time', true)));

        } else if ($this->input->post('bto_time', true)) {

            $to_time = date('H:i:s', strtotime($this->input->post('bto_time', true)));

        }

        if ($this->input->post('block_time_for', true)) {

            $type = $this->input->post('btype', true);

            $reason = $this->input->post('reason', true);

            $repeat = $this->input->post('repeat', true);

            $chief_complaint = "";

            $appointment_type = "";

        } else {

            $type = $this->input->post('atype', true);

            $chief_complaint = $this->input->post('chief_complaint', true);

            $appointment_type = $this->input->post('appointment_type', true);

        }






        $session_id = $this->session->userdata('user_id');

        $created_by_id = $this->session->userdata('created_by');

        $isadmin = $this->session->userdata('isadmin');

        if ($isadmin == 1) {

            $session_id = $created_by_id;

        }

        $doctordetail = $this->db->select("*")->from("user")->where("user_id", $this->input->post('doctor_id', true))->get()->row();

        $pdetail = $this->db->select("*")->from("patient")->where("patient_id", $this->input->post('patent_id', true))->get()->row();




        $data['time_schedule'] = (object)$postData = [

            'schedule_id'    => $this->input->post('schedule_id', true),

            'doctor_id'      => ($this->input->post('doctor_id', true) != '') ? $this->input->post('doctor_id', true) : 0,

            'block_time_for'     => ($this->input->post('block_time_for', true) != '') ? $this->input->post('block_time_for', true) : 0,

            'duration' => ($this->input->post('aduration', true) != '') ? $this->input->post('aduration', true) : $this->input->post('bduration', true),

            'chief_complaint'    => $chief_complaint,

            'appointment_type'   => $appointment_type,

            'whens' => $whens,

            'from_time' => $from_time,

            'to_time' => $to_time,




            'repeat' => $repeat,

            'reason' => $reason,

            'repeat_date' => $repeatdate,




            'note' => ($this->input->post('note', true) != '') ? $this->input->post('note', true) : '',

            'type' => $type,

            'patent_id' => ($this->input->post('patent_id', true) != '') ? $this->input->post('patent_id', true) : '',

            'hospital_id' => $session_id,

            'created_date' => date('Y-m-d h:i:s'),

            'created_by' => $this->session->userdata('user_id'),

            'created_by_role' => $this->session->userdata('user_role')

        ];




        #-------------------------------#

        if ($this->form_validation->run() === true) {







            if ($repeat != '') {




                $doctordetail = $this->db->select("*")->from("user")->where("user_id", $this->input->post('block_time_for', true))->get()->row();

                $pdetail = $this->db->select("*")->from("patient")->where("patient_id", $this->input->post('patent_id', true))->get()->row();

                $begin = new DateTime($whens);

                $stop_date = date('Y-m-d', strtotime($repeatdate . ' +1 day'));

                $end =  new DateTime($stop_date);




                $interval = DateInterval::createFromDateString('1 day');

                $period = new DatePeriod($begin, $interval, $end);




                foreach ($period as $dt) {






                    $arrr['schedule_id'] = $this->input->post('schedule_id', true);

                    $arrr['doctor_id'] = ($this->input->post('doctor_id', true) != '') ? $this->input->post('doctor_id', true) : 0;

                    $arrr['block_time_for'] = ($this->input->post('block_time_for', true) != '') ? $this->input->post('block_time_for', true) : 0;

                    $arrr['duration'] = ($this->input->post('aduration', true) != '') ? $this->input->post('aduration', true) : $this->input->post('bduration', true);

                    $arrr['chief_complaint'] = $chief_complaint;

                    $arrr['appointment_type'] = $appointment_type;

                    $arrr['whens'] = $dt->format('Y-m-d');

                    $arrr['from_time'] = $from_time;

                    $arrr['to_time'] = $to_time;

                    $arrr['repeat'] = $repeat;

                    $arrr['reason'] = $reason;

                    $arrr['description'] = '';

                    $arrr['repeat_date'] = $repeatdate;

                    $arrr['note'] = ($this->input->post('note', true) != '') ? $this->input->post('note', true) : '';

                    $arrr['type'] = $type;

                    $arrr['patent_id'] = ($this->input->post('patent_id', true) != '') ? $this->input->post('patent_id', true) : '';

                    $arrr['hospital_id'] = $session_id;

                    $arrr['created_date'] = date('Y-m-d h:i:s');

                    $arrr['created_by'] = $this->session->userdata('user_id');

                    $arrr['created_by_role'] = $this->session->userdata('user_role');




                    $this->db->insert('schedule', $arrr);

                }

                $inserted_id = $this->db->insert_id();

                if ($inserted_id) {




                    if ($type == "appointment") {

                        $audit_success = insert_auditdump(

                            $this->session->userdata("user_id"),

                            $this->session->userdata("user_role"),

                            "scheduleappointment",

                            "Add  Appointment",

                            $pdetail->fname . " " . $pdetail->lname . " Patient Appointment fixed with " . $doctordetail->firstname . "  " . $doctordetail->lastname . " Medical Provider at " . date('Y-m-d', strtotime($this->input->post('awhens', true))) . " " . date('h:i:s', strtotime($from_time)) . " To " . date('h:i:s', strtotime($to_time)),

                            $this->session->userdata("hospital_id"),

                            $pdetail->id,

                            $pdetail->fname . " " . $pdetail->lname,

                            10

                        );

                    } else {




                        $audit_success = insert_auditdump(

                            $this->session->userdata("user_id"),

                            $this->session->userdata("user_role"),

                            "scheduleappointment",

                            "Add  Block",

                            $doctordetail->firstname . "  " . $doctordetail->lastname . "  Medical Provider Appointment has been blocked with Patient at " . date('Y-m-d', strtotime($this->input->post('bwhens', true))) . " " . date('h:i:s', strtotime($from_time)) . " To " . date('h:i:s', strtotime($to_time)),

                            $this->session->userdata("hospital_id"),

                            $doctordetail->user_id,

                            $doctordetail->firstname . " " . $doctordetail->lastname,

                            2

                        );

                    }




                    $this->session->set_flashdata('message', display('save_successfully'));

                } else {

                    $this->session->set_flashdata('exception', display('please_try_again'));

                }

            } else {

                $doctordetail = $this->db->select("*")->from("user")->where("user_id", $this->input->post('block_time_for', true))->get()->row();

                $pdetail = $this->db->select("*")->from("patient")->where("patient_id", $this->input->post('patent_id', true))->get()->row();




                if ($this->schedule_model->create($postData)) {




                    if ($doctordetail != '' and $pdetail != '') {

                        $to = $doctordetail->email;

                        $ap_type = $this->input->post('appointment_type', true);

                        $dd_when = date('m/d/Y', strtotime($this->input->post('awhens', true)));

                        $ttime = date('h:i A', strtotime($from_time));

                        $subject = $ap_type . " Appointment with " . $pdetail->fname . ' ' . $pdetail->lname . " on " . $dd_when . ' ' . $ttime;

                        $htmlMessage_doc = "Hello " . $doctordetail->firstname . ' ' . $doctordetail->lastname . ',' . "<br><br><br>";

                        $htmlMessage_doc .= "You are scheduled for a " . $ap_type . " appointment with " . $pdetail->fname . ' ' . $pdetail->lname . ".<br><br><br>";

                        $htmlMessage_doc .= "When:  " . date('l,F d,Y', strtotime($this->input->post('awhens', true))) . '  at  ' . $ttime . ".<br><br><br>";

                        $htmlMessage_doc .= "If you have questions or need help, please contact us by phone: XXXXXXX" . "<br><br><br>";

                        $htmlMessage_doc .= "Thank you," . "<br>";

                        $htmlMessage_doc .= "<br><br>";

                        $this->sendEmailAttachment($to, $subject, $htmlMessage_doc);




                        $to_p = $pdetail->email;

                        $ap_type = $this->input->post('appointment_type', true);

                        $dd_when = date('m/d/Y', strtotime($this->input->post('when, true)));

                        $ttime = date('h:i A', strtotime($from_time));

                        $subjectp = $ap_type . " Appointment with " . $doctordetail->firstname . ' ' . $doctordetail->lastname . " on " . $dd_when . ' ' . $ttime;

                        $htmlMessage_p = "Hello " . $pdetail->fname . ' ' . $pdetail->lname . ',' . "<br><br><br>";

                        $htmlMessage_p .= "You are scheduled for a " . $ap_type . " appointment with " . $doctordetail->firstname . ' ' . $doctordetail->lastname . ".<br><br><br>";

                        $htmlMessage_p .= "When:  " . date('l,F d,Y', strtotime($this->input->post('whens', true))) . '  at  ' . $ttime . ".<br><br><br>";

                        $htmlMessage_p .= "We recommend that you reach the venue 30 minutes before your appointment time in order to complete any necessary forms." . "<br><br><br>";




                        $htmlMessage_p .= "If you have questions or need help, please contact us by phone:XXXXXXXX" . "<br><br><br>";

                        $htmlMessage_p .= "Thank you," . "<br>";

                        $htmlMessage_p .= "<br><br>";

                        $this->sendEmailAttachment($to_p, $subjectp, $htmlMessage_p);

                    }







                    if ($type == "appointment") {

                        $audit_success = insert_auditdump(

                            $this->session->userdata("user_id"),

                            $this->session->userdata("user_role"),

                            "scheduleappointment",

                            "Add  Appointment",

                            $pdetail->fname . " " . $pdetail->lname . " Patient Appointment fixed with " . $doctordetail->firstname . "  " . $doctordetail->lastname . " Medical Provider at " . date('Y-m-d', strtotime($this->input->post('awhens', true))) . " " . date('h:i:s', strtotime($from_time)) . " To " . date('h:i:s', strtotime($to_time)),

                            $this->session->userdata("hospital_id"),

                            $pdetail->id,

                            $pdetail->fname . " " . $pdetail->lname,

                            10

                        );

                    } else {

                        $audit_success = insert_auditdump(

                            $this->session->userdata("user_id"),

                            $this->session->userdata("user_role"),

                            "scheduleappointment",

                            "Add  Block",

                            $doctordetail->firstname . "  " . $doctordetail->lastname . " Medical Provider Appointment has been blocked with Patient at " . date('Y-m-d', strtotime($this->input->post('bwhens', true))) . " " . date('h:i:s', strtotime($from_time)) . " To " . date('h:i:s', strtotime($to_time)),

                            $this->session->userdata("hospital_id"),

                            $doctordetail->user_id,

                            $doctordetail->firstname . " " . $doctordetail->lastname,

                            2

                        );

                    }






                    #set success message

                    $this->session->set_flashdata('message', display('save_successfully'));

                } else {

                    #set exception message

                    $this->session->set_flashdata('exception', display('please_try_again'));

                }

            }





            redirect('schedule');

        } else {

            $session_id = $this->session->userdata('user_id');

            $date = date('Y-m-d');

            $data['schedulestoday'] = $this->db->select("*")->from("schedule")->where("whens", $date)->where("hospital_id", $session_id)->order_by('whens', 'asc')->get()->result();

            $data['doctor_list'] = $this->doctor_model->doctor_list();

            $data['content'] = $this->load->view('schedule', $data, true);

            $this->load->view('layout/main_wrapper', $data);

        }

    }