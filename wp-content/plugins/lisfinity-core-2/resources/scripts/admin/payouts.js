/* global lc_data */
import axios from 'axios';

const processPayout = (e) => {
  const data = {};
  const { gateway, id, amount, email } = e.target.dataset;
  const confirmMessage = e.target.dataset.confirm;
  data['gateway'] = gateway;
  data['id'] = id;
  data['amount'] = parseFloat(amount);
  data['email'] = email;

  if (!confirm(confirmMessage)) {
    return false;
  }
  const headers = {
    'X-WP-Nonce': lc_data.nonce,
  };
  axios({
    url: lc_data.payout_process,
    method: 'POST',
    credentials: 'same-origin',
    headers,
    data
  }).then((response) => {
    if (response.data.error) {
      alert(response.data.error);
    }
    if (response.data.success) {
      e.target.remove();
      const sentText = document.getElementById(`paymentAction-${id}`);
      if (sentText) {
        sentText.classList.remove('hidden');
      }
    }
  });
};
const paymentBtns = document.querySelectorAll('.process-payment');
if (paymentBtns) {
  paymentBtns.forEach(btn => {
    btn.addEventListener('click', processPayout);
  });
}

const processMassPayout = (e) => {
  const data = {};
  const confirmMessage = e.target.dataset.confirm;
  if (!confirm(confirmMessage)) {
    return false;
  }

  const headers = {
    'X-WP-Nonce': lc_data.nonce,
  };
  axios({
    url: lc_data.payout_process_mass,
    method: 'POST',
    credentials: 'same-origin',
    headers,
  }).then((response) => {
    if (response.data.error) {
      alert(response.data.error);
    }
    if (response.data.success) {
      const paymentBtns = document.querySelectorAll('.process-payment');
      if (paymentBtns) {
        paymentBtns.forEach(btn => {
          if (response.data.ids.includes(parseInt(btn.dataset.id, 10))) {
            btn.remove();
          }
        });
        response.data.ids.forEach(id => {
          const sentText = document.getElementById(`paymentAction-${id}`);
          if (sentText) {
            sentText.classList.remove('hidden');
          }
        });
      }
    }
  });
};
const massPaymentBtn = document.getElementById('process-mass-payment');
if (massPaymentBtn) {
  massPaymentBtn.addEventListener('click', processMassPayout);
}
