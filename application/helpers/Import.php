<?php
    header('Content-Type: text/html; charset=utf-8');
    /**
    * Import file
    */
    class Import extends Base_Controller
    {
        function __construct()
        {
            parent::__construct();
            $this->load->model('Member_model');
            $this->load->helper(array('form', 'url'));
        }

        function user_import()
        {
            // $exam_id =   $this->input->post('exam_id');
            // $round   =   $this->input->post("round");

            $this->load->library('Excel');

            $config = array();
            //initial upload
            $config['upload_path']          = './uploads/user_excel/';//zip檔與照片
            $config['allowed_types']        = '*';
            $config['encrypt_name']     = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            //upload
            if ( ! $this->upload->do_upload('user_import')) {
                $upload_message = "";
                $error = array('error' => $this->upload->display_errors());
                print_r($error);
                die();
            } else {
                $upload_message =  $this->upload->data();
            }

            // unzip upload file and analysis uploaded excel and insert into DB
            if(isset($upload_message) && $upload_message!=""){
                $file_src = $config['upload_path'] ."/".$upload_message['file_name'];//取得上傳的壓縮檔路徑
                $rand_name = date("Ymdhis").rand(100,999);
                $user_pic_folder = "/uploads/user/";//圖片區
                $user_excel_folder = "/uploads/user_excel/";//EXCEL區
                //開始解壓縮並將excel檔移到/assets/upload_file/user_group_excel/
                $zip = new ZipArchive;
                if ($zip->open($file_src) === TRUE) {
                    $new_file_name = $rand_name.'.xlsx';//excel新檔名
                    if($zip->extractTo(FCPATH.$user_pic_folder)){
                        $old_file_src = FCPATH.$user_pic_folder.'user_data.xlsx';
                        $new_file_src = FCPATH.$user_excel_folder.$new_file_name; 
                        if(!rename( $old_file_src, $new_file_src)) { 
                            echo "<script> alert('檔案更名上傳發生錯誤，請聯繫工程師'); location.href='".base_url()."mgr/user'; </script>";
                            die();
                        }
                    } else {
                        echo FCPATH.$user_pic_folder;
                        echo "<script> alert('檔案解壓縮發生錯誤，請聯繫工程師'); location.href='".base_url()."mgr/user'; </script>";
                        die();
                    }
                    $zip->close();
                } else {
                    echo "<script> alert('檔案上傳發生錯誤，請聯繫工程師'); location.href='".base_url()."mgr/user'; </script>";
                        die();
                }

                $inputFileType = PHPExcel_IOFactory::identify($new_file_src);//自動判別為Excel5或Excel2007或其他
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);//設置Reader
                $objPHPExcel = $objReader->load($new_file_src);//載入Excel
                $sheet = $objPHPExcel->getSheet(0); // 讀取第一個工作表(編號從 0 開始)

                $numRow = $sheet->getHighestRow(); // 取得總列數
                $numColumn = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn())-1;// 取得總行數
                
                //將資料一筆一筆存進陣列後判斷並新增/修改資料庫
                $data_err_count=0;
                $error_describe = array();
                for($row=3; $row<=$numRow; $row++) {
                    $data_OK_flag=true;
                    $data = array();
                    for ($column=0; $column<$numColumn; $column++) { 
                        if($sheet->getCellByColumnAndRow(0, $row)->getValue()==''){
                            if($data_err_count==0){
                                echo "<script> alert('匯入完成');location.href='".base_url()."mgr/user'; </script>";
                                die();
                            }else{
                                // echo "<script> alert('總共有".$data_err_count."筆資料發生錯誤或重複匯入');</script>";
                                // echo "<script> alert('";
                                // foreach ($error_describe as $r) {
                                //     echo $r.'\n';
                                // }
                                // echo "');</script>";
                                // echo "<script> location.href='".base_url()."mgr/user'; </script>";
                                // die();
                                $this->data['title'] = '用戶管理';
                                $this->data['active'] = "user";
                                $this->data["data_err_count"] = $data_err_count;
                                $this->data["error_describe"] = $error_describe;
                                $html = $this->load->view("mgr/error",$this->data,true);
                                echo $html;
                                die();
                                
                            }
                        }
                        //將每筆資料的欄位塞進data
                        $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
                        $index_str = $this->get_column_str($column);
                        $data[$index_str] = $val;
                    }

                    $user_data = array(
                        'player'        => $data["player"],
                        'coach'         => $data["coach"],
                        'referee'       => $data["referee"],
                        'club_official' => $data["club_official"],
                        'account'       =>  $data["account"],
                        'password'      =>  $this->encryption->encrypt(md5($data["password"])),
                        'photo'         =>  $user_pic_folder.$data["photo"],
                        'ID_photo_f'    =>  $user_pic_folder.$data["ID_photo_f"],
                        'ID_photo_b'    =>  $user_pic_folder.$data["ID_photo_b"],
                        'lastname'      =>  $data["lastname"],
                        'firstname'     =>  $data["firstname"],
                        'lastname_en'   =>  $data["lastname_en"],
                        'firstname_en'  =>  $data["firstname_en"],
                        'gender'        =>  $data["gender"],
                        'birthday'      =>  $data["birthday"],
                        'email'         =>  $data["email"],
                        'phone'         =>  $data["phone"],
                        'permanent_address'     =>  $data["permanent_address"],
                        'residential_address'   =>  $data["residential_address"],
                        'AFC_id'        =>  $data["AFC_id"],
                        'school'        =>  $data["school"],
                        'agent_name'    =>  $data["agent_name"],
                        'agent_email'   =>  $data["agent_email"],
                        'agent_phone'   =>  $data["agent_phone"],
                        'agent_ID'      =>  $data["agent_ID"],
                    );

                    if($data['player']==1){
                        $user_data["player_level"] = $data["player_level"];
                    }

                    $user_data["status"] = ($data['status']==1)?"success":"review_pending";


                    //學生資料若已存在user中則修改，不存在則新增，最後都要吐出$user_id
                    if(!$this->Member_model->check_duplication("account",$data["account"])){
                        $res = false;

                        //檢視審查單位正不正確
                        $review = $this->db->get_where("review",["name"=>$data["review_name"]])->row_array();
                        if(isset($review)){
                            $user_data["review_organization"] = $review["id"];
                            $res = $this->db->insert("user", $user_data);
                            $user_id = $this->db->insert_id();
                        }else{
                            array_push($error_describe, "第".($row-2)."筆資料錯誤:用戶審查單位錯誤");
                            $data_OK_flag=false;
                        }
                        
                        if(!$res){
                            array_push($error_describe, "第".($row-2)."筆資料錯誤:新增用戶資料時出現錯誤");
                            $data_OK_flag=false;
                            $data_err_count++;
                        }else{
                            if($data["coach"]==1){
                                $coach_data = array(
                                    'domestic_level'    =>  $data["domestic_level"],
                                    'foreign_level'     =>  $data["foreign_level"],
                                    'coach_ID'          =>  $data["coach_ID"],
                                    'coach_issue_date'  =>  $data["coach_issue_date"],
                                    'coach_expiration_date' =>  $data["coach_expiration_date"],
                                    'member_id' =>  $user_id,
                                );
                                $res = $this->db->insert("coach", $coach_data);
                                if(!$res){
                                    array_push($error_describe, "第".($row-2)."筆資料錯誤:新增教練資料時出現錯誤");
                                    $data_OK_flag=false;
                                    $data_err_count++;
                                }
                            }                            
                            if($data["referee"]==1){
                                $referee_data = array(
                                    'referee_level'         =>  $data["referee_level"],
                                    'referee_ID'            =>  $data["referee_ID"],
                                    'referee_issue_date'    =>  $data["referee_issue_date"],
                                    'referee_expiration_date'   =>  $data["referee_expiration_date"],
                                    'referee_remark'        =>  $data["referee_remark"],
                                    'member_id' =>  $user_id,
                                );
                                $res = $this->db->insert("referee", $referee_data);
                                if(!$res){
                                    array_push($error_describe, "第".($row-2)."筆資料錯誤:新增裁判資料時出現錯誤");
                                    $data_OK_flag=false;
                                    $data_err_count++;
                                }
                            }
                            // if($data["club_official"]==1){
                            //     $club_data = array(
                            //         'type'     =>  $data["club_type"],
                            //         'name'     =>  $data["club_name"],
                            //         'level'  =>  $data["club_level"],
                            //         'company'  =>  $data["club_company"],
                            //         'email'    =>  $data["club_email"],
                            //         'phone'    =>  $data["club_phone"],
                            //         'address'  =>  $data["club_address"],
                            //         'remark'   =>  $data["club_remark"],
                            //         //'official_id' =>  $user_id,
                            //     );

                            //     $res = $this->db->insert("club", $club_data);
                            //     if(!$res){
                            //         array_push($error_describe, "第".($row-2)."筆資料錯誤:新增俱樂部資料時出現錯誤");
                            //         $data_OK_flag=false;
                            //         $data_err_count++;
                            //     }
                            // }
                        }
                    }else{
                        array_push($error_describe, "第".($row-2)."筆資料錯誤:該用戶帳號已存在");
                        $data_OK_flag=false;
                        $data_err_count++;
                    }
                }

                // if($data_err_count==0){
                //     echo "<script> alert('匯入完成');location.href='".base_url()."mgr/user'; </script>";
                //     die();
                // }else{
                //     echo "<script> alert('總共有".$data_err_count."筆資料發生錯誤或重複報名');</script>";
                //     echo "<script> alert('";
                //     foreach ($error_describe as $r) {
                //         echo $r.'\n';
                //     }
                //     echo "');</script>";
                //     echo "<script> location.href='".base_url()."mgr/user'; </script>";
                //     //var_dump($error_describe);
                //     die();
                // }
            }
        }

        public function get_column_str($column){
            if($column==0) return "NO";
            if($column==1) return "status";
            if($column==2) return "player";
            if($column==3) return "player_level";
            if($column==4) return "coach";
            if($column==5) return "referee";
            if($column==6) return "club_official";
            if($column==7) return "nationality";
            if($column==8) return "photo";
            if($column==9) return "ID_photo_f";
            if($column==10) return "ID_photo_b";
            if($column==11) return "account";
            if($column==12) return "password";
            if($column==13) return "lastname";
            if($column==14) return "firstname";
            if($column==15) return "lastname_en";
            if($column==16) return "firstname_en";
            if($column==17) return "gender";
            if($column==18) return "birthday";
            if($column==19) return "email";
            if($column==20) return "phone";
            if($column==21) return "permanent_address";
            if($column==22) return "residential_address";
            if($column==23) return "AFC_id";
            if($column==24) return "school";
            if($column==25) return "agent_name";
            if($column==26) return "agent_email";
            if($column==27) return "agent_phone";
            if($column==28) return "agent_ID";
            if($column==29) return "review_name";
            if($column==30) return "domestic_level";
            if($column==31) return "foreign_level";
            if($column==32) return "coach_ID";
            if($column==33) return "coach_issue_date";
            if($column==34) return "coach_expiration_date";
            if($column==35) return "referee_level";
            if($column==36) return "referee_ID";
            if($column==37) return "referee_issue_date";
            if($column==38) return "referee_expiration_date";
            if($column==39) return "referee_remark";
            if($column==40) return "club_type";
            if($column==41) return "club_name";
            if($column==42) return "club_level";
            if($column==43) return "club_company";
            if($column==44) return "club_email";
            if($column==45) return "club_phone";
            if($column==46) return "club_address";
            if($column==47) return "club_remark";
            return NULL;
        }

        function club_import()
        {
            $this->load->library('Excel');

            $config = array();
            //initial upload
            $config['upload_path']          = './uploads/user_excel/';//zip檔與照片
            $config['allowed_types']        = '*';
            $config['encrypt_name']     = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            //upload
            if ( ! $this->upload->do_upload('club_import')) {
                $upload_message = "";
                $error = array('error' => $this->upload->display_errors());
                print_r($error);
                die();
            } else {
                $upload_message =  $this->upload->data();
            }

            if(isset($upload_message) && $upload_message!=""){
                $file_src = FCPATH.$config['upload_path'] ."/".$upload_message['file_name'];//取得上傳的檔案路徑

                $inputFileType = PHPExcel_IOFactory::identify($file_src);//自動判別為Excel5或Excel2007或其他
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);//設置Reader
                $objPHPExcel = $objReader->load($file_src);//載入Excel
                $sheet = $objPHPExcel->getSheet(0); // 讀取第一個工作表(編號從 0 開始)

                $numRow = $sheet->getHighestRow(); // 取得總列數
                $numColumn = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());// 取得總行數
                
                //將資料一筆一筆存進陣列後判斷並新增/修改資料庫
                $data_err_count=0;
                $error_describe = array();
                for($row=2; $row<=$numRow+1; $row++) {
                    $data_OK_flag=true;
                    $data = array();
                    for ($column=0; $column<$numColumn; $column++) { 
                        if($sheet->getCellByColumnAndRow(0, $row)->getValue()==''){
                            if($data_err_count==0){
                                echo "<script> alert('匯入完成');location.href='".base_url()."mgr/club'; </script>";
                                die();
                            }else{
                                $this->data['title'] = '俱樂部管理';
                                $this->data['active'] = "club";
                                $this->data["data_err_count"] = $data_err_count;
                                $this->data["error_describe"] = $error_describe;
                                $html = $this->load->view("mgr/error",$this->data,true);
                                echo $html;
                                die();
                                
                            }
                        }
                        //將每筆資料的欄位塞進data
                        $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
                        if($column==0)$index_str = 'club_account';
                        if($column==1)$index_str = 'user_account';
                        if($column==2)$index_str = 'type'; //player or coach
                        $data[$index_str] = $val;
                    }

                    if($data['club_account'] == "" || $data['user_account'] == "" ){
                        array_push($error_describe, "第".($row-1)."筆資料錯誤:尚有未填資料");
                        $data_OK_flag=false;
                        $data_err_count++;
                        continue;
                    }

                    if($data['type'] != "player" && $data['type'] != "coach" ){
                        array_push($error_describe, "第".($row-1)."筆資料錯誤:type欄位須為player或coach");
                        $data_OK_flag=false;
                        $data_err_count++;
                        continue;
                    }

                    $club = $this->db->get_where('club',['account'=>$data['club_account'],'is_delete'=>0])->row_array();
                    if(!isset($club)){
                        array_push($error_describe, "第".($row-1)."筆資料錯誤:該帳號找不到俱樂部資料");
                        $data_OK_flag=false;
                        $data_err_count++;
                        continue;
                    }
                    $user = $this->db->get_where('user',['account'=>$data['user_account'],'is_delete'=>0])->row_array();
                    if(!isset($user)){
                        array_push($error_describe, "第".($row-1)."筆資料錯誤:該帳號找不到會員資料");
                        $data_OK_flag=false;
                        $data_err_count++;
                        continue;
                    }
                    $check = $this->db->where(
                                " (member_id=".$user["id"]." AND club_id=".$club['id']." AND type='".$data['type']."') AND (status='now' OR status='remove_pending' OR status='pending')"
                            )->get("club_member_ref")->row_array();
                    if(isset($check)){
                        array_push($error_describe, "第".($row-1)."筆資料錯誤:該會員已用".$data['type']."身分加入該俱樂部");
                        $data_OK_flag=false;
                        $data_err_count++;
                        continue;
                    }

                    $this->db->insert('club_member_ref',array(
                        'type'          => $data['type'],
                        'club_id'       => $club["id"],
                        'member_id'     => $user["id"],
                        'self_agree'    => 'yes',
                        'agent_agree'   => 'yes',
                        'admin_agree'   => 'yes',
                        'status'        => 'now',
                    ));
                }

            }else{
                echo "<script> alert('檔案上傳發生錯誤，請聯繫工程師'); location.href='".base_url()."mgr/club'; </script>";
                        die();
            }
        }


    }