
async function patchFaqData(data) {
  console.log(data);
  const url = `../api/api_FAQ.php?id=${data.id}&${encodeForAjax({ displayed: data.displayed })}`;

  return await fetch(url, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: encodeForAjax(data)
  });
}

const editFaqBtns = document.querySelectorAll('#editFaqBtn');
const deleteFaqBtns = document.querySelectorAll('#deleteFaqBtn');
const answerBtns = document.querySelectorAll('#answerFaq');
const displayFaqBtns = document.querySelectorAll('#displayBtn');
const hideFaqBtns = document.querySelectorAll('#hideBtn');

// !XXX: Funtions to add listeners

function handleEdit(editFaqBtn) {
  const faq = editFaqBtn.parentElement;
  // const message = faq.nextElementSibling;
  const feedback = faq.nextElementSibling;
  const question = faq.querySelector('#question');
  const answer = faq.querySelector('#answer');
  const saveFaqBtn = faq.querySelector('#saveFaqBtn');
  const answerBtn = faq.querySelector('#answerFaq');
  if (answerBtn) { editFaqBtn.toggleAttribute('hidden'); }

  const toggle = () => {
    editFaqBtn.toggleAttribute('hidden');
    saveFaqBtn.toggleAttribute('hidden');
    // deleteFaqBtn.toggleAttribute('hidden');

    question.toggleAttribute('readonly');
    question.classList.toggle("input-readonly");
    question.classList.toggle("input-write");

    answer.toggleAttribute('readonly');
    answer.classList.toggle("input-readonly");
    answer.classList.toggle("input-write");

  }

  editFaqBtn.addEventListener('click', () => {
    toggle();
  });

  saveFaqBtn.addEventListener('click', async () => {
    console.log(question.value);
    console.log(answer.value);

    const json = await putData('../api/api_FAQ.php', { 'id': faq.getAttribute("data-id"), 'question': question.value, 'answer': answer.value });


    if (json['error']) {
      displayFeedback(feedback,json);
    }
    else {
      console.log("success");
      displayFeedback(feedback, json);
      toggle();
    }
  });
};

function handleDelete(deleteFaqBtn) {
  const faq = deleteFaqBtn.parentElement;
  const feedback = faq.nextElementSibling;
  const modal = faq.querySelector(".modal");
  const modalContent = modal.querySelector(".modalContent");
  const x = modal.querySelector(".close");
  const confirm = modal.querySelector(".confirm-del");

  // const answerBtn = faq.querySelector('#aswerFaq');
  // if (answerBtn.hasAttribute('hidden')) {deleteFaqBtn.toggleAttribute('hidden');}


  deleteFaqBtn.addEventListener('click', async (event) => {
    toggleModal();
    event.preventDefault();
    event.stopPropagation();
    document.addEventListener('click', clickOnDocument);
  })

  x.addEventListener("click", () => {
    toggleModal();
    document.removeEventListener('click', clickOnDocument);
  });
  
  function toggleModal() {
    modal.classList.toggle("d-none");
  }

  function clickOnDocument(event) {
    if (modalContent.contains(event.target)) return;
    event.preventDefault();
    event.stopPropagation();
    toggleModal();
    document.removeEventListener('click', clickOnDocument);
  }

  confirm.addEventListener("click", async () => {
    const json = await deleteData('../api/api_FAQ.php', {'id': faq.getAttribute("data-id")});
    if (json['error']) {
      displayFeedback(feedback, json);
    }
    else {
      faq.remove();
      displayFeedback(feedback, json, true);
    }
  });

};

function handleAnswer(answerBtn) {
  const faq = answerBtn.parentElement;
  const feedback = faq.nextElementSibling;
  console.log(feedback);
  const question = faq.querySelector('#question');
  const answer = faq.querySelector('#answer');
  const saveFaqBtn = faq.querySelector('#saveFaqBtn');
  const editFaqBtn = faq.querySelector('#editFaqBtn');
  const deleteFaqBtn = faq.querySelector('#deleteFaqBtn');
  const displayBtn = faq.querySelector('#displayBtn');
  const saveAnsBtn = faq.querySelector('#saveAnswerBtn');

  // editFaqBtn.toggleAttribute('hidden');
  // deleteFaqBtn.toggleAttribute('hidden');

  const toggle = () => {
    answerBtn.toggleAttribute('hidden');
    saveAnsBtn.toggleAttribute('hidden');

    answer.toggleAttribute('readonly');
    answer.classList.toggle("input-readonly");
    answer.classList.toggle("input-write");

    // saveFaqBtn.toggleAttribute('hidden');
  }

  const appear = () => {
    /*appear:*/
    displayBtn.toggleAttribute('hidden');
    // deleteFaqBtn.toggleAttribute('hidden');
    editFaqBtn.toggleAttribute('hidden');

    /*readonly:*/
    answer.toggleAttribute('readonly');
    answer.classList.toggle("input-readonly"); /*this is related to visual aspect -> css*/
    answer.classList.toggle("input-write");
  }

  answerBtn.addEventListener('click', () => {
    toggle();

  });

  saveAnsBtn.addEventListener('click', async () => {
    console.log(question.value);
    console.log(answer.value);

    const json = await putData('../api/api_FAQ.php', {
      'id': faq.getAttribute("data-id"),
      'question': question.value,
      'answer': answer.value
    });
    
    
    if (json['error']) {
      displayFeedback(feedback,json);
    }
    else {
      displayFeedback(feedback,json);
      appear();
      answerBtn.remove();
      saveAnsBtn.remove();
    }
  });

};

function handleDisplay(displayBtn) {
  const faq = displayBtn.parentElement;
  const feedback = faq.nextElementSibling;
  console.log(faq);
  const hideBtn = faq.querySelector('#hideBtn');
  const answerBtn = faq.querySelector('#answerFaq');
  if (answerBtn) { displayBtn.toggleAttribute('hidden'); }

  displayBtn.addEventListener('click', async () => {
    displayBtn.toggleAttribute('hidden');
    hideBtn.toggleAttribute('hidden');
    const res = await patchFaqData({ 'id': faq.getAttribute("data-id"), 'displayed': '1' });
    const json = await res.json();
    if (res.ok) {
      displayFeedback(feedback,json);
      console.log("success");
    }
    else {
      console.error('Error: ' + res.status);
      // displayMessage(json['error']);
      // displayFeedback("add-faq-feedback",json);
      displayFeedback(feedback,json);

    }
  })
};

function handleHide(hideBtn) {
  const faq = hideBtn.parentElement;
  const feedback = faq.nextElementSibling;
  const displayBtn = faq.querySelector('#displayBtn');

  hideBtn.addEventListener('click', async () => {
    hideBtn.toggleAttribute('hidden');
    displayBtn.toggleAttribute('hidden');
    const res = await patchFaqData({ 'id': faq.getAttribute("data-id"), 'displayed': '0' });
    const json = await res.json();
    if (res.ok) {
      console.log("success");
      // displayMessage(json['success'], false);
      // displayFeedback("add-faq-feedback",json);
      displayFeedback(feedback,json);
    }
    else {
      console.error('Error: ' + res.status);
      // displayMessage(json['error']);
      // displayFeedback("add-faq-feedback",json);
      displayFeedback(feedback,json);
    }
  })
};


if (editFaqBtns) {
  editFaqBtns.forEach((editFaqBtn) => {
    handleEdit(editFaqBtn);
  });

  deleteFaqBtns.forEach((deleteFaqBtn) => {
    handleDelete(deleteFaqBtn);
  });

  displayFaqBtns.forEach((displayFaqBtn) => {
    handleDisplay(displayFaqBtn);
  });

  hideFaqBtns.forEach((hideFaqBtn) => {
    handleHide(hideFaqBtn);
  });

  if (answerBtns) {
    answerBtns.forEach((answerBtn) => {
      handleAnswer(answerBtn);
    })
  }

}
