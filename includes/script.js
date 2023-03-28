// Получения поля выбора карт
const cardSelect = document.getElementById("card-select"); // текущая карта
let expirationWarning = document.getElementById("expiration-warning"); // предупреждение об истекшем сроке действия карты
let btn_continue_card_valid = document.getElementById("btn-continue-card-valid"); // кнопка "Продолжить, карта действительная"
let btn_submit = document.getElementById("btn-submit"); // кнпока "Отправить заявку"

// Вызов обработчика change на элементе cardSelect
const event = new Event('change'); // создаем событие change
cardSelect.dispatchEvent(event); // запускаем обработчик события change на элементе cardSelect


// Добавляем слушатель события change на элемент select
cardSelect.addEventListener("change",function() {
    // Скрытие сообщение об успешной отправки формы при выборе другой карты
    document.getElementById("success-message").style.display = "none";

    // Получение текущей карты
    let selectedOption = cardSelect.options[cardSelect.selectedIndex];
    let card_date = selectedOption.getAttribute('data-creation'); // текущая дата карты
    let card_number = cardSelect.value // текущий номер карты

    console.log(card_date);
    console.log(card_number)

    // Сегодняшняя дата
    const today = new Date();
    const today_year = today.getFullYear()
    const today_month = today.toLocaleString('en-US', { month: '2-digit' });

    // Дата создания карты
    const dateParts = card_date.split('/');
    const card_date_year = parseInt(dateParts[1], 10);
    const card_date_month = parseInt(dateParts[0], 10).toString().padStart(2, '0');

    // Проверка действительности карты
    let card_validity_period = document.getElementById("card-validity-period");
    if ((card_date_year <= today_year) && (card_date_month <= today_month)) {
        // сообщение об ошибки
        expirationWarning.innerHTML =
            'Карта "' +
            card_number +
            '" является более не действительной на ' +
            today_month + "/" + today_year +
            ", так как срок ее действия прошел " +
            card_date_month + "/" + card_date_year;
        expirationWarning.style.display = "block";

        // вывод текущей даты карты
        card_validity_period.innerHTML = "Срок действия карты: " + card_date_month + "/" + card_date_year;
        card_validity_period.style.display = "block";

        // отображение кнопки "Продолжить, карта действительная"
        btn_continue_card_valid.style.display = "block"

        // блокировка кнопки
        btn_submit.setAttribute('disabled', true);
    } else {
        // убираем сообщение об ошибке
        expirationWarning.style.display = "none";

        // вывод текущей даты карты
        card_validity_period.innerHTML = "Срок действия карты: " + card_date_month + "/" + card_date_year;
        card_validity_period.style.display = "block";

        // отображение кнопки "Продолжить, карта действительная"
        btn_continue_card_valid.style.display = "none"

        // включение кнопки
        btn_submit.removeAttribute('disabled');
    }
})

// Слушатель нажатия на кнопку "Продолжить, карта действительная"
document.getElementById("btn-continue-card-valid").addEventListener("click", function () {
    expirationWarning.style.display = "none";
    btn_continue_card_valid.style.display = "none"
    btn_submit.removeAttribute('disabled');
})

// Функция для отправки заявки
function submitApplication(event) {
    event.preventDefault();
    if (cardSelect.value !== "") {
        let successMessage = document.getElementById("success-message");
        successMessage.style.display = "block";
    }

}