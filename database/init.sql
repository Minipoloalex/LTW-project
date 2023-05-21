PRAGMA foreign_keys=ON;
DROP TABLE IF EXISTS FOLLOWING;
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


/*******************************************************************************
   Create Tables
********************************************************************************/


CREATE TABLE CLIENT(
    UserID INTEGER PRIMARY KEY,
    Name VARCHAR(120) NOT NULL,
    Username VARCHAR(255) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(60) NOT NULL UNIQUE
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

/* admin <- agent <- client */

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
    
    /*List all changes done to a ticket (e.g., status changes, assignments, edits).*/
);

CREATE TABLE DEPARTMENT(
    DepartmentID INTEGER,
    DepartmentName VARCHAR(255) NOT NULL,
    PRIMARY KEY (DepartmentID)
);

CREATE TABLE HASHTAG(
    HashtagID INTEGER PRIMARY KEY,
    HashtagName VARCHAR(50)
);

CREATE TABLE HASHTAG_TICKET(    /* many to many */
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

CREATE TABLE FOLLOWING (
    TicketID INTEGER NOT NULL,
    AgentID INTEGER NOT NULL,
    FOREIGN KEY (TicketID) REFERENCES TICKET(TicketID) ON DELETE CASCADE,
    FOREIGN KEY (AgentID) REFERENCES AGENT(UserID) ON DELETE CASCADE,
    PRIMARY KEY (TicketID, AgentID)
);

CREATE TABLE IMAGE(
    ImageID INTEGER NOT NULL PRIMARY KEY
);
