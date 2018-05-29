<?php
require_once __DIR__ . '/config.php';
class Database{
    private $db;
    private $pagelimit = 10;
    public function __construct()
    {
        $this->db = new MysqliDb (HOST,USER,PASSWORD,DATABASE);
    }

     public function insertaClausula($uid, $clausula){
        $ok = false;
        $data = Array ("uid" => $uid,
                 "clausula" => $clausula, "fecha" => date("Y-m-d h:i:sa"), "estado" => 0
                 );
          $id = $this->db->insert ('clausulas', $data);
          if($id){
               return $id;
          } else {
              echo 'insert failed: ' . $this->db->getLastError(); die;
          }
      }
      public function updateClausula($uid, $clausula){

         $data = Array (
                  "clausula" => $clausula
                  );
                  $this->db->where ('id', $uid);
           $id = $this->db->update ('clausulas', $data);
           if($id){
                return $id;
           } else {
               echo 'update failed: ' . $this->db->getLastError(); die;
           }
       }

      public function existeEmail($email) {
             $ents = $this->db->rawQueryOne('select * from participaciones where email=?', Array($email));
             if(!empty($ents)){
                 return true;
             } else {
                 return false;
             }
      }
      public function existeFbid($fbid) {
             $ents = $this->db->rawQueryOne('select * from participaciones where fbid=?', Array($fbid));
             if(!empty($ents)){
                 return true;
             } else {
                 return false;
             }
      }
      public function getClausulas($pag, $estado){
        $ini = $pag * $this->pagelimit;
        $ciudades = $this->db->rawQuery("SELECT SQL_CALC_FOUND_ROWS c.*,u.Ffname, (SELECT COUNT(v.clausula_id) as votos) as votos FROM clausulas c LEFT JOIN users u on c.uid=u.UID LEFT JOIN votaciones v on c.id=v.clausula_id WHERE estado = '$estado' GROUP BY c.id ORDER BY c.id DESC LIMIT $ini, $this->pagelimit ");

          //  print_r($this->db->getLastQuery());

             return array($ciudades,$this->getClausulasTotalPages($estado) );
      }

     private function getClausulasTotalPages( $estado ) {

          $this->db->join("users u", "c.uid=u.UID", "LEFT");
          $this->db->orderBy("c.id","desc");
          $this->db->page = 1;
             // set page limit to 2 results per page. 20 by default
         $this->db->pageLimit = $this->pagelimit;
          $this->db->where("estado", $estado);
          $ciudades = $this->db->paginate('clausulas c', 1 );
          return $this->db->totalPages;
     }

      public function getClausulasUid($pag, $uid){
                $this->db->join("users u", "c.uid=u.UID", "LEFT");
                $this->db->join("votaciones v", "c.id=v.clausula_id", "LEFT");
                $this->db->joinWhere("votaciones v", "v.uid", $uid);
                $this->db->orderBy("c.fecha_aprovado","desc");
                $this->db->page =  $pag;
                $this->db->pageLimit = 10000;
                $this->db->where("c.estado", 1);// set page limit to 2 results per page. 20 by default
              //  $this->db->orderBy("votos", "DESC");
                 $ciudades = $this->db->paginate('clausulas c', $pag, "c.id, c.clausula, u.Ffname, v.id as vid" );
              //   print_r($this->db->getLastQuery());
                // $ciudades[0]['total']=$this->db->totalPages;
              //  echo $this->db->totalcount;
                return array($ciudades,$this->db->count);
              }

        public function getClausulasUidRanking($pag, $uid){
                        $this->db->join("users u", "c.uid=u.UID", "LEFT");
                        $this->db->join("votaciones v", "c.id=v.clausula_id", "LEFT");
                        $this->db->joinWhere("votaciones v", "v.uid", $uid);
                        $this->db->page =  $pag;
                        $this->db->pageLimit = 10000;
                        $this->db->where("c.estado", 1);// set page limit to 2 results per page. 20 by default
                        $this->db->orderBy("votos", "DESC");
                         $ciudades = $this->db->paginate('clausulas c', $pag, "c.id, c.clausula, u.Ffname, v.id as vid, (SELECT COUNT(*) FROM votaciones where clausula_id = c.id ) as votos" );
                      //   print_r($this->db->getLastQuery());
                        // $ciudades[0]['total']=$this->db->totalPages;
                        return array($ciudades, $this->db->count);
                      }

      public  function checkuser($fuid,$ffname,$femail){
          $ents = $this->db->rawQueryOne('select * from users where Fuid=?', Array($fuid));

          if(!empty($ents)){
            $data = Array ( "lastlogin" => date("Y-m-d h:i:sa"));
                $this->db->where ('Fuid', $fuid);
                $id = $this->db->update ('users', $data);
                if ($id)
                    return $id;
                else
                    return 'update failed: ' . $this->db->getLastError();

          } else {
            $data = Array ("Fuid" => $fuid,
                     "Ffname" => $ffname,
                     "Femail" => $femail,
                     "lastlogin" => date("Y-m-d h:i:sa"),
                     "created" => date("Y-m-d h:i:sa"),
                     );
              $id = $this->db->insert ('users', $data);
              if($id){
                   return $id;
              } else {
                  echo 'insert failed: ' . $this->db->getLastError(); die;
              }
          }
    }

    public function getUserid($fuid) {
        $this->db->where("Fuid", $fuid);
        $user = $this->db->getOne("users");
        return $user['UID'];

    }
    public function votar($uid, $clausula){
       $ok = false;
       $data = Array ("uid" => $uid,
                "clausula_id" => $clausula );
         $id = $this->db->insert ('votaciones', $data);
         if($id){
              return $id;
         } else {
             echo 'insert failed: ' . $this->db->getLastError(); die;
         }
     }

     public function existeUserAdmin($username, $password) {
       $ents = $this->db->rawQueryOne('select * from useradmin where username=? AND password=?', Array($username, $password));
       if(!empty($ents)){
           return true;
       } else {
           return false;
       }
     }

     public function cambiarEstadoClausula($cid, $estado) {
              $data = Array ('estado' => $estado );

       if($estado = '1') {
            $data['fecha_aprovado'] = date("Y-m-d h:i:sa");
       }

       $this->db->where ('id', $cid);
       if ($this->db->update ('clausulas', $data)) {
          // echo $this->db->count . ' records were updated';
       } else
           echo 'update failed: ' . $this->db->getLastError();
     }

     public function tieneClausulas($uid) {
       $ents = $this->db->rawQueryOne('select * from clausulas where uid=?', Array($uid));
       if(!empty($ents)){
           return true;
       } else {
           return false;
       }
     }
}
