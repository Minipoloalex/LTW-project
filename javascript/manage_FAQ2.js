async function postFaqData(data) {
  console.log(data);
  console.log(encodeForAjax(data))
  return await fetch('../api/api_edit_FAQ.php', {
    method: 'post',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: encodeForAjax(data)
  })
}

const editFaqBtns = document.querySelectorAll('#editFaqBtn');

if (editFaqBtns) {
  editFaqBtns.forEach((editFaqBtn) => {

    editFaqBtn.addEventListener('click', () => {
      const faq = editFaqBtn.parentElement;
      console.log(faq);

      const question = faq.querySelector('#question');
      const answer = faq.querySelector('#answer');
      const saveFaqBtn = faq.querySelector('#saveFaqBtn');

     const toggle = () => {
      editFaqBtn.toggleAttribute('hidden');
      saveFaqBtn.toggleAttribute('hidden');

      question.toggleAttribute('readonly');
      question.classList.toggle("input-readonly");
      question.classList.toggle("input-write");

      answer.toggleAttribute('readonly');
      answer.classList.toggle("input-readonly");
      answer.classList.toggle("input-write");
    }
      toggle();

      // !FIXME: edit->save->edit->...já não consigo dar save. WHY?

      saveFaqBtn.addEventListener('click', () => {
        
        // !TODO: request to api

        toggle()


      });

    });
  });
}

// !TODO: answer faq

// !TODO: delete faq

// !TODO: hide/show faq

