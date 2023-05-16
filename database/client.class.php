<?php

    declare(strict_types = 1);

    class Client {
        public int $id;
        public string $name;
        public string $username;
        public string $password;
        public string $email;
        public function __construct(int $id, string $name, string $username, string $password, string $email)
        {
            $this->id = $id;
            $this->name = $name;
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
        }
     
        function save($db) {
            $stmt = $db->prepare('
              UPDATE CLIENT SET Name = ?
              WHERE UserId = ?
            ');
      
            $stmt->execute(array($this->name, $this->id));
          }

        static function getClientWithPassword(PDO $db, string $email, string $password) : ?Client {
          $stmt = $db->prepare('
              SELECT *
              FROM CLIENT
              WHERE lower(Email) = ?
            ');
            $stmt->execute(array(strtolower($email)));
            $client = $stmt->fetch();

            if ($client && password_verify($password, $client['Password'])) {
              return new Client(
                intval($client['UserID']),
                $client['Name'],
                $client['Username'],
                $client['Password'],
                $client['Email']
              );
            }
            return null;
          }
        static function create_account(PDO $db, string $name, string $username, string $email, string $password, string $confirm_password) : int {
          $stmt = $db->prepare('INSERT INTO CLIENT (Name, Username, Email, Password) VALUES (?, ?, ?, ?)');

          $options = ['cost' => 12];
          $stmt->execute(array($name, $username, $email, password_hash($password, PASSWORD_DEFAULT, $options)));

          return intval($db->lastInsertId());
        }
        static function check_acc_exists(PDO $db, string $username, string $email) {
          $stmt = $db->prepare('SELECT * FROM CLIENT WHERE Username = ?');
          $stmt->execute(array(strtolower($username)));
          if ($stmt->fetch()) {
              return array(true, "Username already exists");
          }
          $stmt = $db->prepare('SELECT * FROM CLIENT WHERE Email = ?');
          $stmt->execute(array(strtolower($email)));
          if ($stmt->fetch()) {
              return array(true, "Email already exists");
          }
          return array(false, "Account registered");
      }


        static function searchClients(PDO $db, string $search, int $count) : array {
            $stmt = $db->prepare('SELECT UserID, Name, Username, Password, Email FROM CLIENT WHERE Name LIKE ? LIMIT ?');
            $stmt->execute(array($search . '%', $count));
        
            $clients = array();
            while ($client = $stmt->fetch()) {
              $clients[] = new Client(
                intval($client['UserID']),
                    $client['Name'],
                    $client['Username'],
                    $client['Password'],
                    $client['Email']
              );
            }
        
            return $clients;
          }
        
        

        static function getClients(PDO $db, int $count)  : array {
            $stmt = $db->prepare('SELECT UserID, Name, Username, Password, Email FROM CLIENT LIMIT ? ');
            $stmt->execute(array($count));

            $clients = array();
            while($client = $stmt->fetch()){
                $clients[] = new Client(
                    intval($client['UserID']),
                    $client['Name'],
                    $client['Username'],
                    $client['Password'],
                    $client['Email']
                );
            }
            return $clients;
        }


        static function getById(PDO $db, int $id) : Client {
            $stmt = $db->prepare('SELECT UserID, Name, Username, Password, Email FROM CLIENT WHERE UserID = ?');
            $stmt->execute(array($id));
        
            $client = $stmt->fetch();
            return new Client(
                intval($client['UserID']),
                $client['Name'],
                $client['Username'],
                $client['Password'],
                $client['Email']
            );
          }

          function name() {
            return $this->username;
          } //not really needed in ours


          static function getType(PDO $db, int $id) : string {
           if (Client::isAdmin($db, $id)) return "Admin";

            $stmt = $db->prepare('SELECT * FROM AGENT WHERE UserID = ?');
            $stmt->execute(array($id));
            if ($stmt->fetch()) return "Agent";
            return "Client";
          }
          static function isAdmin(PDO $db, int $userID): bool {
            $stmt = $db->prepare('SELECT * FROM ADMIN WHERE UserID = ?');
            $stmt->execute(array($userID));
            return $stmt->fetch() != NULL;
        }
          static function hasAcessToTicket(PDO $db, int $userID, int $ticketID) {
            if (Client::isAdmin($db, $userID)) return true;
            if (Agent::isValidId($db, $userID)) return true;
            $ticket = Ticket::getById($db, $ticketID);
            $user = Client::getById($db, $userID);
            // no assigned agent can be a plain client, so no need to check if it's an agent
            if ($ticket->username == $user->username) return true;
            return $ticket->assignedagent == $user->username;
          }
          static function canChangeTicketInfo(PDO $db, int $userID, $ticketID) {
            $ticket = Ticket::getById($db, $ticketID);
            $user = Client::getById($db, $userID);
            $type = Client::getType($db, $userID);
            if ($ticket->username === $user->username) return false;
            if ($type === 'Admin') return true;
            if ($type !== 'Agent') return false;

            // agents can change their department's tickets and null department tickets
            // if agent has null department, he can change every ticket
            if ($ticket->departmentName === NULL) return true;
            
            $agent = Agent::getById($db, $userID);
            if ($agent->departmentid === NULL) return true;
            
            $department = Department::getById($db, $agent->departmentid);
            return $department->departmentName === $ticket->departmentName;
          }
          static function filter(PDO $db, array $types, array $departments, string $search) : array {
            $query = 'SELECT UserID, Name, Username, Password, Email FROM CLIENT';
            $typesF = '';
            $departmentsF = '';
            $nameF = '';

            if(!empty($types)){
              for ($i = 0; $i<count($types); $i++) {
                if ($i == 0) {$statusF = $statusF.sprintf('(T.Status = %s', $types[$i]);} 
                else {$statusF = $statusF.sprintf(' or T.Status = %s', $types[$i]);}
              }
              $statusF = $statusF.')';
            }
          }

          static function getFilters(PDO $db): array {
            $filters = [];
            $type = ['Client', 'Agent', 'Admin'];
            $departments = [];
        
            $filters[]=$type;
        
            $stmt = $db->prepare('SELECT * FROM DEPARTMENT;');
            $stmt->execute();
            while ($dp = $stmt->fetch()) {
              $departments[] = $dp;
            }
            $filters[] = $departments;        
        
            return $filters;
          }

          /*for edit profile*/

          function isEmailEqual(PDO $db, string $email) : bool {
            return $this->email == $email;
          }

          function isNameEqual(PDO $db, string $name) : bool {
            return $this->name == $name;
          }

          function isUsernameEqual(PDO $db, string $username) : bool {
            return $this->username == $username;
          }

          function isPassEqual(PDO $db, string $pass) : bool {
            return password_verify($pass, $this->password);
          }

          static function getByEmail(PDO $db, string $email) : ?Client {
            $stmt = $db->prepare('SELECT UserID, Name, Username, Password, Email FROM CLIENT WHERE Email = ?');
            $stmt->execute(array(strtolower($email)));
            
            $client = $stmt->fetch();

            if ($client == NULL) return NULL;
            
            return new Client(
              intval($client['UserID']),
              $client['Name'],
              $client['Username'],
              $client['Password'],
              $client['Email']
            );
          }

          static function getByUsername(PDO $db, string $username) : ?Client {
            $stmt = $db->prepare('SELECT UserID, Name, Username, Password, Email FROM CLIENT WHERE Username = ?');
            $stmt->execute(array($username));
        
            $client = $stmt->fetch();

            if ($client == NULL) return NULL;

            return new Client(
              intval($client['UserID']),
              $client['Name'],
              $client['Username'],
              $client['Password'],
              $client['Email']
            );
          }

          function updateUserInfo(PDO $db, string $name, string $username, string $email) : bool {
            $stmt = $db->prepare('UPDATE CLIENT SET Name = ?, Username = ?, Email = ? WHERE UserID = ?');
            return $stmt->execute(array($name, $username, $email, $this->id));
          }
          
          function updatePass(PDO $db, string $pass) : bool {
            $stmt = $db->prepare('UPDATE CLIENT SET Password = ? WHERE UserID = ?');
            return $stmt->execute(array(password_hash($pass, PASSWORD_DEFAULT), $this->id));
          }
          static function demoteToClient(PDO $db, int $userID) {
            $stmt = $db->prepare('DELETE FROM AGENT WHERE UserID = ?');
            $stmt->execute(array($userID));
          }
          static function demoteToAgent(PDO $db, int $userID) {
            $stmt = $db->prepare('DELETE FROM ADMIN WHERE UserID = ?');
            $stmt->execute(array($userID));
          }
          static function upgradeToAdminFromAgent(PDO $db, int $userID) {
            $stmt = $db->prepare('INSERT INTO ADMIN (UserID) VALUES (?)');
            $stmt->execute(array($userID));
          }
          static function upgradeToAdminFromClient(PDO $db, int $userID) {
            $stmt = $db->prepare('INSER INTO AGENT (UserID, Department) VALUES (?, NULL)');
            $stmt->execute(array($userID));

            $stmt = $db->prepare('INSERT INTO ADMIN (UserID) VALUES (?)');
            $stmt->execute(array($userID));
          }
          static function upgradeToAgent(PDO $db, int $userID) {
            $stmt = $db->prepare('INSERT INTO AGENT (UserID, Department) VALUES (?, NULL)');
            $stmt->execute(array($userID));
          }
    }

    
