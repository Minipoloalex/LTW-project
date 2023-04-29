<?php
declare(strict_types = 1);

class Forum {
    public int $forumId;
    public string $question;
    public ?string $answer;
    public int $displayed;
    public function __construct(int $forumId, string $question, ?string $answer, int $displayed) {
        $this->forumId = $forumId;
        $this->question = $question;
        $this->answer = $answer;
        $this->displayed = $displayed;
    }

    static function getFaqs(PDO $db, int $count): array {
        $stmt = $db->prepare('SELECT * FROM FORUM LIMIT ? ');
        $stmt->execute(array($count));

        $faqs = array();
        while ($faq = $stmt->fetch()) {
            $faqs[] = new Forum(
                intval($faq['ForumID']),
                $faq['Question'],
                $faq['Answer'],
                intval($faq['Displayed'])
            );
        }
        return $faqs;
    }

    static function getFaq(PDO $db, string $question, string $answer): ?Forum {
        $stmt = $db->prepare('SELECT * FROM FORUM WHERE Question = ? AND Answer = ?');
        $stmt->execute(array($question, $answer));

        $faq = $stmt->fetch();
        if (!$faq) {
            return null;
        }

        return new Forum(
            intval($faq['ForumID']),
            $faq['Question'],
            $faq['Answer'],
            intval($faq['Displayed'])
        );
    }

    static function addFaq(PDO $db, string $question): Forum {
        // $question = trim($question);
        $stmt = $db->prepare('INSERT INTO FORUM (Question) VALUES (?)');
        $stmt->execute(array($question));

        return new Forum(
            intval($db->lastInsertId()),
            $question,
            null,
            0
        );
    }

    static function getFaqWithoutAnswer(PDO $db, int $count): array {
        $stmt = $db->prepare('SELECT * FROM FORUM WHERE Answer IS NULL LIMIT ? ');
        $stmt->execute(array($count));

        $faqs = array();
        while ($faq = $stmt->fetch()) {
            $faqs[] = new Forum(
                intval($faq['ForumID']),
                $faq['Question'],
                $faq['Answer'],
                0
            );
        }
        return $faqs;
    }

    static function updateFaq(PDO $db, string $question, string $answer, string $forumID): Forum {
        $stmt = $db->prepare('UPDATE FORUM SET Question = :question, Answer = :answer WHERE ForumID = :id');
        // $stmt = $db->prepare('UPDATE FORUM SET Question = "A", Answer = "B" WHERE ForumID = 2');
        // $stmt->execute();
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        
        $id = intval($forumID);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // $stmt->execute(array($question, $answer, $id));
        // $stmt->execute(array("A", "B", $id));
        echo json_encode(array(
            'question' => $question,
            'answer' => $answer,
            'id' => $id,
        ));
        

        return new Forum(
            intval($forumID),
            $question,
            $answer,
            1   
        );
    }

    static function deleteFaq(PDO $db, string $question, string $answer): bool {
        $stmt = $db->prepare('DELETE FROM FORUM WHERE Question = ? AND Answer = ?');
        $stmt->execute(array($question, $answer));

        return true;
    }
}

?>
