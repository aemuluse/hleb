
// Сделать обработку ошибочных запросов

function setTimeUNIX() {
  let response = request("get_time_unix");
  response.then(body => {
    let result = body["result"]
    if (typeof result !== "undefined")
    {
      document.getElementById('time').innerHTML = result;
    }
    else
    {
      console.log(body);
    }
  })
  .catch(console.log.bind(console));
  
  return response
}

function setTimeMySQL() {
  let response = request("get_time_mysql");
  response.then(body => {
    let result = body["result"]
    if (typeof result !== "undefined")
    {
      document.getElementById('time').innerHTML = result;
    }
    else
    {
      console.log(body);
    }
  })
  .catch(console.log.bind(console));
  
  return response
}

function request(method) {
  let url = "/api";
  let options = {
    method: "POST",
    body: '{"jsonrpc":"2.0","method":"'+ method + '","id":1}',
    // body: JSON.stringify(ob)
  };

  return fetch(url, options)
  .then((response) => {
    return response.json()
  })
  .catch(console.log.bind(console));
}