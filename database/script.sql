BEGIN TRANSACTION;
PRAGMA foreign_keys=ON;
DROP TABLE IF EXISTS HASHTAG_TICKET;
DROP TABLE IF EXISTS HASHTAG;
DROP TABLE IF EXISTS ACTION;
DROP TABLE IF EXISTS MESSAGE;
DROP TABLE IF EXISTS DEPARTMENT;
DROP TABLE IF EXISTS ADMIN;
DROP TABLE IF EXISTS AGENT;
DROP TABLE IF EXISTS CLIENT;
DROP TABLE IF EXISTS TICKET;
DROP TABLE IF EXISTS FORUM;
DROP TABLE IF EXISTS IMAGE;

CREATE TABLE HASHTAG(
    HashtagID INTEGER PRIMARY KEY,
    HashtagName VARCHAR(50)
);
CREATE TABLE DEPARTMENT(
    DepartmentID INTEGER,
    DepartmentName VARCHAR(255) NOT NULL,
    PRIMARY KEY (DepartmentID)
);
CREATE TABLE CLIENT(
    UserID INTEGER PRIMARY KEY,
    Name VARCHAR(120) NOT NULL,
    Username VARCHAR(255) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(60) NOT NULL UNIQUE
);
CREATE TABLE IMAGE(
    ImageID INTEGER NOT NULL PRIMARY KEY
);
CREATE TABLE AGENT(
    UserID INTEGER,
    DepartmentID INTEGER,
    CONSTRAINT "PK_Agent" PRIMARY KEY (UserID)
    FOREIGN KEY (UserID) REFERENCES CLIENT(UserID) ON DELETE CASCADE,
    FOREIGN KEY(DepartmentID) REFERENCES DEPARTMENT(DepartmentID) ON DELETE SET NULL
);
CREATE TABLE ADMIN(
    UserID INTEGER,
    CONSTRAINT "PK_Admin" PRIMARY KEY(UserID),
    FOREIGN KEY (UserID) REFERENCES AGENT(UserID) ON DELETE CASCADE
);
CREATE TABLE TICKET(
    TicketID INTEGER,
    Title VARCHAR(50),
    UserID INTEGER,
    Status VARCHAR(255) NOT NULL DEFAULT 'open',
    SubmitDate INTEGER NOT NULL, 
    Priority VARCHAR(255),
    Description TEXT,
    AssignedAgent INTEGER, /* can be null */
    DepartmentID INTEGER,  /* can be null */
    
    CONSTRAINT "PK_Ticket" PRIMARY KEY(TicketID),
    FOREIGN KEY (UserID) REFERENCES CLIENT(UserID) ON DELETE CASCADE,
    FOREIGN KEY (AssignedAgent) REFERENCES AGENT(UserID) ON DELETE SET NULL,
    FOREIGN KEY (DepartmentID) REFERENCES DEPARTMENT(DepartmentID) ON DELETE SET NULL
);
CREATE TABLE HASHTAG_TICKET(
    TicketID INTEGER,
    HashtagID INTEGER,
    FOREIGN KEY (TicketID) REFERENCES TICKET(TicketID) ON DELETE CASCADE,
    FOREIGN KEY (HashtagID) REFERENCES HASHTAG(HashtagID) ON DELETE CASCADE,
    PRIMARY KEY (TicketID, HashtagID)
);
CREATE TABLE ACTION(
    ActionID INTEGER PRIMARY KEY,
    TicketID INTEGER,
    UserID INTEGER,
    Type VARCHAR(255), /*open, process, close, transfer*/
    TimeStamp INTEGER NOT NULL,

    FOREIGN KEY (TicketID) REFERENCES TICKET(TicketID) ON DELETE CASCADE,
    FOREIGN KEY (UserID) REFERENCES Client(UserID) ON DELETE CASCADE
);
CREATE TABLE MESSAGE(
    MessageID INTEGER PRIMARY KEY,
    TicketID INTEGER,
    UserID INTEGER,
    MessageText TEXT,
    ImageID INTEGER UNIQUE,
    TimeStamp INTEGER NOT NULL,

    FOREIGN KEY (TicketID) REFERENCES TICKET(TicketID) ON DELETE CASCADE,
    FOREIGN KEY (UserID) REFERENCES Client(UserID) ON DELETE CASCADE,
    FOREIGN KEY (ImageID) REFERENCES IMAGE(ImageID) ON DELETE SET NULL
);
CREATE TABLE FORUM(
    ForumID INTEGER PRIMARY KEY,
    Question TEXT,
    Answer TEXT,
    Displayed INTEGER
);
INSERT INTO CLIENT VALUES(7,'Chris Evans','admin_chris','$2y$10$4I7d7iGBoYuj0U6yB0yp5ugSAQ.gO4jkqFFlvc9UCrhwOQ36lAOd.','adminchris@gmail.com');
INSERT INTO CLIENT VALUES(8,'Sofia','sofia','$2y$10$/i2f8Sfw6zBb8t2IsUNaTeP5My4uCoLRP083aD3V6KGeLlDT8puwW','sofia@gmail.com');
INSERT INTO CLIENT VALUES(9,'Felix','felix_martins','$2y$12$pc6VAsha1S8wVfvyQj2N/eGPzEY1amW9uK2l7DPAw1XwI/gxGZAZK','hello@gmail.com');
INSERT INTO CLIENT VALUES(11,'Guilherme','gui','$2y$10$.ZRhBX4p2WKB24UfaNsL0uic89Zw.EZ6X32ds.PZGUYbUakOSeWpi','gui@gmail.com');
INSERT INTO CLIENT VALUES(12,'Emma Thompson','emmathompson','$2y$12$WDKroqSjyukVL2sPD1AW6.9CDJYwSkFWC4YXjMjvHtGNxbDJfhaO.','emmathompson@example.com');
INSERT INTO CLIENT VALUES(13,'Noah Clark','noahclark','$2y$12$WS5RSbSFC1WSK9fRYtTmUO6sqmsvhW6oqtf8i1XHAG9ErPQneWT46','noahclark@example.com');
INSERT INTO CLIENT VALUES(14,'Liam Smith','liamsmith','$2y$12$I8lkeefViyT6/uMR58moretpqxLcyK5fl7ge1n44/NoJiiloqc6Sa','clientliam@gmail.com');
INSERT INTO CLIENT VALUES(15,'Ethan Lewis','elewis89','$2y$12$u3kZmV6JoPleVk1nLfrZY.P9VNV7.97OP775S/PXKTZVeT5yjVf4W','elewis@gmail.com');
INSERT INTO CLIENT VALUES(16,'Mia Taylor','miataylor11','$2y$12$y67DzVBnvEHnUI/ruxWmbOIqDol55SHD8xVDs3gzIVblogJjU.lc.','miataylor@gmail.com');
INSERT INTO CLIENT VALUES(17,'James Robinson','jrobinson34','$2y$12$EGX97uOsyn9xO0kg8i4Q9.RU/O5xtvBzBcQ4RMYyBiqyc7No/U/cy','jrobinson@gmail.com');
INSERT INTO CLIENT VALUES(18,'Ava Martinez','avamartinez77','$2y$12$XG6UnqqeBLw8ggXCQ8bOy.DaaXDICg6YBuA0lwnLfmMhpCArjBxgm','avamartinez@gmail.com');
INSERT INTO CLIENT VALUES(19,'Benjamin Harris','bharris21','$2y$12$P7HNevsXkd69LJDgjA3L6eyN3TD4NivNZmSRdIAy1NccjoxzC79CO','bharris@gmail.com');
INSERT INTO CLIENT VALUES(20,'Sophie Anderson','sophiee98','$2y$12$tXJv1UKqaYYOwtL2AR5lGOSuxs1fXHcpquDUMOi9A7KupdwlcNLqi','sophie@gmail.com');
INSERT INTO CLIENT VALUES(21,'Daniel Clark','dclark2000','$2y$12$GIjB3VkBqML7x1MglKsxwuS/P9iZgF3h0xQ96ZADScg05FZOkHypW','dclark@gmail.com');
INSERT INTO CLIENT VALUES(22,'Olivia Brown','obrown56','$2y$12$ec.R4RM1bCFhC40SiZR36OJF0tyWXQMNWvxJoNihOy2y4pi62mYKG','obrown@gmail.com');
INSERT INTO CLIENT VALUES(23,'David Miller','davemiller78','$2y$12$O5lKB3agO.wWAba/w8cst.mZSk4JcyVfmUR1zPRhnozb1iTc4L3DW','davemiller@gmail.com');
INSERT INTO CLIENT VALUES(24,'Sarah Thompson','sarahthompson45','$2y$12$tDylilshdWQPCcDCx86uAOyY03sG0VEhWotGSXAXUmqoewTzGX7bm','sarahthompson@gmail.com');
INSERT INTO CLIENT VALUES(25,'Michael Wilson','mwilson123','$2y$12$WH0cMZFLNx5wR.vUkbTuZulnQ/Xn6cFAtriaMYavC2HknY0V9seGq','mwilson@gmail.com');
INSERT INTO CLIENT VALUES(26,'Emily Davis','emilydavis99','$2y$12$Bp78uzpgbozqSN51aoeZLeVkgZFvRo.rzuhV1jrQWX/3QbAj9uD.y','emilydavis@gmail.com');
INSERT INTO CLIENT VALUES(27,'Robert Johnson','robjohnson22','$2y$12$00q1Ia2g8UfcJmZszzxi..PpdPri3F7QgK.3WMBIdWufJoJQsAaHu','robjohnson@gmail.com');

INSERT INTO IMAGE VALUES(5);
INSERT INTO IMAGE VALUES(6);
INSERT INTO DEPARTMENT VALUES(1,'Sales');
INSERT INTO DEPARTMENT VALUES(2,'Support');
INSERT INTO DEPARTMENT VALUES(3,'Billing');
INSERT INTO DEPARTMENT VALUES(4,'Technical');
INSERT INTO DEPARTMENT VALUES(5,'Marketing');
INSERT INTO DEPARTMENT VALUES(6,'Human Resources');
INSERT INTO DEPARTMENT VALUES(7,'Engineering');
INSERT INTO DEPARTMENT VALUES(8,'Product');
INSERT INTO DEPARTMENT VALUES(9,'Design');
INSERT INTO DEPARTMENT VALUES(10,'Customer Success');
INSERT INTO DEPARTMENT VALUES(11,'Finance');
INSERT INTO DEPARTMENT VALUES(12,'Legal');
INSERT INTO DEPARTMENT VALUES(13,'Operations');
INSERT INTO DEPARTMENT VALUES(14,'Research and Development');
INSERT INTO DEPARTMENT VALUES(15,'Information Technology');
INSERT INTO DEPARTMENT VALUES(16,'Quality Assurance');
INSERT INTO DEPARTMENT VALUES(17,'Purchasing');
INSERT INTO DEPARTMENT VALUES(18,'Training and Development');
INSERT INTO DEPARTMENT VALUES(19,'Facilities Management');
INSERT INTO DEPARTMENT VALUES(20,'Public Relations');

INSERT INTO AGENT VALUES(7,15);
INSERT INTO AGENT VALUES(8,15);
INSERT INTO AGENT VALUES(9,15);
INSERT INTO AGENT VALUES(11,13);
INSERT INTO AGENT VALUES(13,15);
INSERT INTO AGENT VALUES(20,NULL);
INSERT INTO AGENT VALUES(21,1);
INSERT INTO AGENT VALUES(23,15);
INSERT INTO AGENT VALUES(24,13);
INSERT INTO AGENT VALUES(25,5);
INSERT INTO AGENT VALUES(26,2);

INSERT INTO ADMIN VALUES(7);
INSERT INTO ADMIN VALUES(9);
INSERT INTO ADMIN VALUES(20);
INSERT INTO ADMIN VALUES(24);
INSERT INTO ADMIN VALUES(25);
INSERT INTO ADMIN VALUES(26);

INSERT INTO TICKET VALUES(1,'Network Issues',8,'open',1684532682,'medium','The employees in the Accounting department are experiencing intermittent network connectivity issues. They are unable to access the accounting software and connect to the shared network drive.',NULL,4);
INSERT INTO TICKET VALUES(2,'Software Compatibility',8,'closed',1684532847,'medium','The marketing team has recently installed a new graphic design software, but they are facing compatibility issues with their existing project management software. They are unable to import design files into the project management tool.',NULL,16);
INSERT INTO TICKET VALUES(3,'Printing Error',8,'open',1684533550,'medium','The Human Resources department''s printer is consistently producing blurry and smudged prints. Employees are unable to print important documents and forms clearly, causing delays in HR processes',NULL,4);
INSERT INTO TICKET VALUES(4,'Slow System Performance',7,'open',1684535053,'high','The system has become noticeably slow, and it is impacting my productivity. Simple tasks like loading pages and running reports are taking much longer than usual',NULL,16);
INSERT INTO TICKET VALUES(7,'Website Navigation',9,'assigned',1684688756,'low','I am having difficulty navigating through the website. The menu options are not working properly, and some links are leading to incorrect pages.',9,15);
INSERT INTO TICKET VALUES(8,'Email Delivery Issue',11,'assigned',1684689001,'high','I am experiencing problems with email delivery. Some of my outgoing emails are not reaching the recipients, and I have received complaints from clients about not receiving important communications.',9,15);
INSERT INTO TICKET VALUES(9,'Data Backup Request',12,'open',1684689201,'medium','I need assistance with setting up regular data backups for our company. We want to ensure that our data is secure and protected from any potential loss or damage.',NULL,13);
INSERT INTO TICKET VALUES(10,'Mobile App Crashing',14,'assigned',1684689401,'high','The mobile app keeps crashing every time I try to open it. I have tried reinstalling it, but the issue persists. It is affecting my ability to access important features and complete tasks.',9,15);
INSERT INTO TICKET VALUES(12,'Operating System Update ',8,'open',1684696470,'medium','I am encountering an error while updating my operating system. The update process fails or gets stuck at a specific point. Assist me in resolving this operating system update error.',NULL,15);
INSERT INTO TICKET VALUES(13,'Mobile Device Battery',8,'open',1684696529,'high','My mobile device''s battery is draining rapidly, even when not in use. It used to last much longer before. Please help me identify the cause and improve the battery life.',NULL,16);
INSERT INTO TICKET VALUES(14,'Network Printer Config',8,'open',1684696581,'low','I am trying to configure a network printer but cannot get it to work. The printer is not detected on the network, and I cannot print documents. Help me with the network printer configuration.',NULL,15);
INSERT INTO TICKET VALUES(15,'User Account Access',7,'assigned',1684696696,'high','A user is reporting difficulties accessing their account. They are unable to log in or are experiencing authentication errors. Please investigate and resolve the issue to restore their account access.',NULL,15);
INSERT INTO TICKET VALUES(16,'Security Incident',7,'open',1684696733,'low','An unusual security event has been detected, indicating a potential breach or unauthorized access to sensitive data. Initiate an investigation, assess the impact, and implement necessary security measures.',NULL,NULL);
INSERT INTO TICKET VALUES(17,'Database Performance',7,'open',1684696805,'high','The database server is experiencing performance issues, resulting in slow query execution and high resource utilization. Analyze the database performance metrics, optimize queries, and fine-tune the database configuration for improved performance',NULL,16);
INSERT INTO TICKET VALUES(18,'Application Deployment ',7,'open',1684696852,'low','An application deployment to a specific server or environment has failed. The deployment process encountered errors or inconsistencies. Assist in troubleshooting and ensuring a successful deployment.',NULL,15);


INSERT INTO HASHTAG VALUES(1,'#customer_issue');
INSERT INTO HASHTAG VALUES(2,'#bug_report');
INSERT INTO HASHTAG VALUES(3,'#billing_issue');
INSERT INTO HASHTAG VALUES(4,'#technical_issue');
INSERT INTO HASHTAG VALUES(5,'#customer_support');
INSERT INTO HASHTAG VALUES(6,'#technical_support');
INSERT INTO HASHTAG VALUES(7,'#billing_support');
INSERT INTO HASHTAG VALUES(8,'#account_issue');
INSERT INTO HASHTAG VALUES(9,'#payment_problem');
INSERT INTO HASHTAG VALUES(10,'#feature_request');
INSERT INTO HASHTAG VALUES(11,'#data_security');
INSERT INTO HASHTAG VALUES(12,'#user_experience');
INSERT INTO HASHTAG VALUES(13,'#performance_issue');
INSERT INTO HASHTAG VALUES(14,'#software_update');
INSERT INTO HASHTAG VALUES(15,'#network_outage');
INSERT INTO HASHTAG VALUES(16,'#hardware_failure');
INSERT INTO HASHTAG VALUES(17,'#data_loss');
INSERT INTO HASHTAG VALUES(18,'#data_recovery');
INSERT INTO HASHTAG VALUES(19,'#installation_problem');
INSERT INTO HASHTAG VALUES(20,'#compatibility_issue');
INSERT INTO HASHTAG VALUES(21,'#user_interface');
INSERT INTO HASHTAG VALUES(22,'#mobile_app_issue');
INSERT INTO HASHTAG VALUES(23,'#web_design');
INSERT INTO HASHTAG VALUES(24,'#server_maintenance');
INSERT INTO HASHTAG VALUES(25,'#password_reset');
INSERT INTO HASHTAG VALUES(26,'#email_delivery');
INSERT INTO HASHTAG VALUES(27,'#spam_filter');
INSERT INTO HASHTAG VALUES(28,'#account_activation');
INSERT INTO HASHTAG VALUES(29,'#privacy_concern');
INSERT INTO HASHTAG VALUES(30,'#refund_request');
INSERT INTO HASHTAG VALUES(31,'#product_return');
INSERT INTO HASHTAG VALUES(32,'#shipping_delay');
INSERT INTO HASHTAG VALUES(33,'#order_tracking');
INSERT INTO HASHTAG VALUES(34,'#pricing_plan');
INSERT INTO HASHTAG VALUES(35,'#account_verification');
INSERT INTO HASHTAG VALUES(36,'#user_access');
INSERT INTO HASHTAG VALUES(37,'#analytics_reporting');
INSERT INTO HASHTAG VALUES(38,'#API_integration');
INSERT INTO HASHTAG VALUES(39,'#documentation_help');
INSERT INTO HASHTAG VALUES(40,'#training_resources');
INSERT INTO HASHTAG VALUES(41,'#video_tutorial');
INSERT INTO HASHTAG VALUES(42,'#knowledge_base');
INSERT INTO HASHTAG VALUES(43,'#FAQs');
INSERT INTO HASHTAG VALUES(44,'#community_forum');
INSERT INTO HASHTAG VALUES(45,'#social_media_support');

INSERT INTO HASHTAG_TICKET VALUES(1,13);
INSERT INTO HASHTAG_TICKET VALUES(1,19);
INSERT INTO HASHTAG_TICKET VALUES(2,14);
INSERT INTO HASHTAG_TICKET VALUES(3,4);
INSERT INTO HASHTAG_TICKET VALUES(3,13);
INSERT INTO HASHTAG_TICKET VALUES(4,13);
INSERT INTO HASHTAG_TICKET VALUES(4,6);
INSERT INTO HASHTAG_TICKET VALUES(12,13);
INSERT INTO HASHTAG_TICKET VALUES(12,38);
INSERT INTO HASHTAG_TICKET VALUES(12,14);
INSERT INTO HASHTAG_TICKET VALUES(13,11);
INSERT INTO HASHTAG_TICKET VALUES(13,18);
INSERT INTO HASHTAG_TICKET VALUES(13,13);
INSERT INTO HASHTAG_TICKET VALUES(13,2);
INSERT INTO HASHTAG_TICKET VALUES(14,13);
INSERT INTO HASHTAG_TICKET VALUES(14,29);
INSERT INTO HASHTAG_TICKET VALUES(15,28);
INSERT INTO HASHTAG_TICKET VALUES(15,8);
INSERT INTO HASHTAG_TICKET VALUES(15,35);
INSERT INTO HASHTAG_TICKET VALUES(15,36);
INSERT INTO HASHTAG_TICKET VALUES(15,12);
INSERT INTO HASHTAG_TICKET VALUES(15,21);
INSERT INTO HASHTAG_TICKET VALUES(15,13);
INSERT INTO HASHTAG_TICKET VALUES(16,13);
INSERT INTO HASHTAG_TICKET VALUES(17,11);
INSERT INTO HASHTAG_TICKET VALUES(17,42);
INSERT INTO HASHTAG_TICKET VALUES(17,13);
INSERT INTO HASHTAG_TICKET VALUES(18,13);

INSERT INTO "ACTION" VALUES(2,12,8,'sofia created this ticket',1684696470);
INSERT INTO "ACTION" VALUES(3,13,8,'sofia created this ticket',1684696529);
INSERT INTO "ACTION" VALUES(4,14,8,'sofia created this ticket',1684696581);
INSERT INTO "ACTION" VALUES(5,15,7,'admin_chris created this ticket',1684696696);
INSERT INTO "ACTION" VALUES(6,16,7,'admin_chris created this ticket',1684696733);
INSERT INTO "ACTION" VALUES(7,17,7,'admin_chris created this ticket',1684696805);
INSERT INTO "ACTION" VALUES(8,18,7,'admin_chris created this ticket',1684696852);
INSERT INTO "ACTION" VALUES(9,7,7,'admin_chris edited this ticket''s information and assigned the ticket to felix_martins',1684697046);
INSERT INTO "ACTION" VALUES(10,8,7,'admin_chris edited this ticket''s information and assigned the ticket to felix_martins',1684697096);
INSERT INTO "ACTION" VALUES(11,10,7,'admin_chris edited this ticket''s information and assigned the ticket to felix_martins',1684697127);
INSERT INTO "ACTION" VALUES(12,15,7,'admin_chris closed this ticket',1684697159);
INSERT INTO "ACTION" VALUES(13,4,7,'admin_chris edited this ticket''s information',1684707589);
INSERT INTO "ACTION" VALUES(14,4,7,'admin_chris closed this ticket',1684707911);
INSERT INTO "ACTION" VALUES(15,15,7,'admin_chris reopened this ticket',1684707944);
INSERT INTO "ACTION" VALUES(16,15,7,'admin_chris edited this ticket''s information and assigned the ticket to felix_martins',1684708485);
INSERT INTO "ACTION" VALUES(17,15,7,'admin_chris edited this ticket''s information and assigned the ticket to liamsmith',1684708613);
INSERT INTO "ACTION" VALUES(18,4,7,'admin_chris reopened this ticket',1684708742);

INSERT INTO MESSAGE VALUES(1,4,7,'answer please',NULL,1684709129);
INSERT INTO MESSAGE VALUES(2,4,7,'fast!',NULL,1684709134);
INSERT INTO MESSAGE VALUES(3,4,7,'look',5,1684709152);
INSERT INTO MESSAGE VALUES(4,4,7,'this error:',6,1684709225);

INSERT INTO FORUM VALUES(13,'How do I register a new account?','To register a new account, click on the Sign Up button on the website''s landing page. Fill out the required information, such as your name, username, password, and email address. ',0);
INSERT INTO FORUM VALUES(14,'How can I update my profile information?','To update your profile information, go to the Profile section. There, you can edit your name, username, password, and email address. Make sure to save the changes after updating your profile.',1);
INSERT INTO FORUM VALUES(15,'Can I reply to inquiries or add more information to my submitted tickets?','Yes, you can reply to inquiries or add more information to your submitted tickets. The agent can ask for more details about your ticket. Simply access the ticket details and use the message option to provide the requested information. You can also add additional details or images to your ticket as needed.',0);
INSERT INTO FORUM VALUES(16,'Can I view the history of changes made to my tickets?',NULL,NULL);
INSERT INTO FORUM VALUES(17,'How can I track the tickets I have submitted?',NULL,NULL);
INSERT INTO FORUM VALUES(18,'How can I submit a new ticket on the website?','After logging in, navigate to the Create Ticket section. Provide the necessary details, such as the ticket description, department (if applicable), and any relevant informations. Click on the button to create the ticket.',1);
COMMIT;
