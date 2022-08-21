let isRunning = false;
let timerHandler = null;

/**
 * Запускает процессы отправки и вывода запросов
 * @param {string} method - название метода для вызова
 * ("UNIX"/"MySQL") (не чувствительно к регистру)
 */
function setTime(method) {
  // Останавливаем таймер, если запущен
  if (isRunning) {
    clearInterval(timerHandler);
    isRunning = false;
  }

  // Отправляем запросы с интервалом в 1 секунду
  // и сохраняем handle для для остановки
  timerHandler = setInterval(handle, 1000, method);
  isRunning = true;
}

/**
 * Отправляет запрос на сервер и выводит ответ
 * @param {string} method - название метода для вызова
 * @returns Promise-объект
 */
function handle(method) {
  method = "get_time_" + method.toLowerCase();
  let response = this.request(method);

  response.then(body => {
    let result = body["result"];
    if (typeof result !== "undefined")
    {
      document.getElementById('time').innerHTML = result;
    }
    else
    {
      console.log(body)
    }
  })
  .catch(console.log.bind(console));

  return response;
}

/**
 * Формирует запрос и возвращает ответ с сервера
 * @param {string} rpcMethod - название вызываемого метода
 * @returns {Promise}
 */
function request(rpcMethod) {
  let url = "/api";
  let rpcBody = {
    "jsonrpc": "2.0",
    "method": rpcMethod,
    "id": 1
  }
  let options = {
    method: "POST",
    body: JSON.stringify(rpcBody)
  };

  return fetch(url, options)
  .then((response) => {
    return response.json()
  })
  .catch(console.log.bind(console));
}
