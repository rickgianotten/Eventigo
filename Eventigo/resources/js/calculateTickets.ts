const totalPriceEl = document.getElementById('total_price');
const ticketsAmountEl = document.getElementById('total_tickets');
const tickets = document.querySelectorAll<HTMLInputElement>('.ticket');

let totalPrice = 0;
let totalAmountOfTickets = 0;

function parseEuropeanNumber(input: string | null): number {
  if (!input) return 0;
  const normalized = input.replace(/\./g, '').replace(',', '.');
  const result = parseFloat(normalized);
  return isNaN(result) ? 0 : Math.round(result * 100) / 100;
}

function formatEuropeanNumber(value: number, decimals: number = 2): string {
  return value.toLocaleString('nl-NL', {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals,
  });
}

function updateTotals() {
  if (totalPriceEl) totalPriceEl.textContent = formatEuropeanNumber(totalPrice) == "-0,00" ? "0,00" :formatEuropeanNumber(totalPrice);
  if (ticketsAmountEl) ticketsAmountEl.textContent = totalAmountOfTickets.toString();
}

tickets.forEach((ticket) => {
  const ticketInput = ticket.querySelector<HTMLInputElement>('.ticket_quantity');
  const ticketPriceEl = ticket.querySelector('.ticket_price');
  const btnDecrease = ticket.querySelector<HTMLButtonElement>('.btn_decrease');
  const btnIncrease = ticket.querySelector<HTMLButtonElement>('.btn_increase');
  const maxQuantity = Number(ticket.querySelector('.max_quantity_of_tickets')?.textContent);

  if (!ticketInput || !ticketPriceEl || !btnDecrease || !btnIncrease) return;

  let ticketQuantity = Number(ticketInput.value);

  function getTicketPrice(): number {
    return parseEuropeanNumber(ticketPriceEl!.textContent);
  }

  function updateButtons() {
    btnIncrease!.disabled = ticketQuantity >= maxQuantity;
    btnDecrease!.disabled = ticketQuantity <= 0;
  }

  // ticketInput.addEventListener('input',()=>{
  //   if (ticketQuantity >= maxQuantity) return;
  //   if (ticketQuantity < maxQuantity) return;

  //   const ticketPrice = getTicketPrice();

  //   ticketQuantity = Number(ticketInput.value);

  //   totalPrice += ticketPrice * ticketQuantity;
  //   totalAmountOfTickets += ticketQuantity;

  //   updateTotals();
  //   updateButtons();

  // });

  btnIncrease.addEventListener('click', () => {
    if (ticketQuantity >= maxQuantity) return;

    ticketQuantity++;
    ticketInput!.value = ticketQuantity.toString();
    totalPrice += getTicketPrice();
    totalAmountOfTickets++;

    updateTotals();
    updateButtons();
  });

  btnDecrease.addEventListener('click', () => {
    if (ticketQuantity <= 0) return;

    ticketQuantity--;
    ticketInput!.value = ticketQuantity.toString();
    totalPrice -= getTicketPrice();
    totalAmountOfTickets--;

    updateTotals();
    updateButtons();
  });

  updateButtons(); // zet initiële staat van de knoppen
});