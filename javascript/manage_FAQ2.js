// async function putFaqData(data) {
//   console.log(data);
//   return await fetch('../api/api_edit_FAQ.php', {
//     method: 'PUT',
//     headers: {
//       'Content-Type': 'application/x-www-form-urlencoded'
//     },
//     body: encodeForAjax(data)
//   })
// }


async function putFaqData(data) {
  console.log(data);
  // const url = `../api/api_edit_FAQ.php?id=${data.id}&question=${encodeURIComponent(data.question)}&answer=${encodeURIComponent(data.answer)}`;
  const url = `../api/api_FAQ.php?id=${data.id}&${encodeForAjax({ question: data.question, answer: data.answer })}`;
  return await fetch(url, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: encodeForAjax(data)
  })
}

async function deleteFaqData(data) {
  console.log(data);
  return await fetch(`../api/api_FAQ.php?id=${data.id}`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: encodeForAjax(data)
  })
}

const editFaqBtns = document.querySelectorAll('#editFaqBtn');
const deleteFaqBtns = document.querySelectorAll('#deleteFaqBtn');
const answerBtns = document.querySelectorAll('#answerFaq');


// !XXX: Funtions to add listeners

function handleEdit(editFaqBtn) {
  const faq = editFaqBtn.parentElement;

  const question = faq.querySelector('#question');
  const answer = faq.querySelector('#answer');
  const saveFaqBtn = faq.querySelector('#saveFaqBtn');
  const answerBtn = faq.querySelector('#answerFaq');
  if (answerBtn) { editFaqBtn.toggleAttribute('hidden'); }
  // const deleteFaqBtn = faq.querySelector('#deleteFaqBtn');
  console.log(question.value);
  console.log(answer.value);

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

    const res = await putFaqData({ 'id': faq.getAttribute("data-id"), 'question': question.value, 'answer': answer.value });

    if (res.ok) {
      const a = await res.json();
      console.log(a);
      console.log("success");
      toggle();
    } else {
      console.error('Error: ' + res.status);
    }

  });
};

function handleDelete(deleteFaqBtn) {
  const faq = deleteFaqBtn.parentElement;

  const answerBtn = faq.querySelector('#aswerFaq');
  // if (answerBtn.hasAttribute('hidden')) {deleteFaqBtn.toggleAttribute('hidden');}


  deleteFaqBtn.addEventListener('click', async () => {
    const res = await deleteFaqData({ 'id': faq.getAttribute("data-id") });
    if (res.ok) {
      console.log("success");
      // delete element
      faq.remove();
    } else {
      console.error('Error: ' + res.status);
    }
  })
};

function handleAnswer(answerBtn) {
  const faq = answerBtn.parentElement;
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

    const res = await putFaqData({ 'id': faq.getAttribute("data-id"), 'question': question.value, 'answer': answer.value });

    if (res.ok) {
      const a = await res.json();
      console.log(a);
      console.log("success");
      appear();
      answerBtn.remove();
      saveAnsBtn.remove();
      // appear();
    } else {
      console.log(res);
      console.error('Error: ' + res.status);
    }

  });

};

if (editFaqBtns) {
  editFaqBtns.forEach((editFaqBtn) => {

    handleEdit(editFaqBtn);
    // deleteFaqBtn.addEventListener('click', async () => {
    //   // !TODO: request to api to delete faq
    //   const res = await deleteFaqData({ 'id': faq.getAttribute("data-id") });
    //   if (res.ok) {
    //     console.log("success");
    //     // delete element
    //     faq.remove();
    //   } else {
    //     console.error('Error: ' + res.status);
    //   }
    // })
  });

  deleteFaqBtns.forEach((deleteFaqBtn) => {
    // deleteFaqBtn.toggleAttribute('hidden');
    handleDelete(deleteFaqBtn);
  }
  )

}

if (answerBtns) {
  answerBtns.forEach((answerBtn) => {

    handleAnswer(answerBtn);

  })
}



// !TODO: answer faq

// !TODO: delete faq

// !TODO: hide/show faq
