const timer = document.getElementById("game-timer");

if (timer) {
  const config = JSON.parse(timer.dataset.time);
  const state = config.state;

  const startTime = new Date(config.startTime.date);
  const lastUpdate = state === "resume" ? new Date() : new Date(config.lastUpdate?.date ?? new Date());

  if (state === "resume") {
    const elapsed = lastUpdate - startTime;
    const minutes = Math.floor(elapsed / 60000);
    const seconds = Math.floor((elapsed % 60000) / 1000);
    const formattedTime = (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;

    timer.innerText = formattedTime;

    start(minutes, seconds);
  } else if (state === "stop") {
    const elapsed = lastUpdate - startTime;
    const minutes = Math.floor(elapsed / 60000);
    const seconds = Math.floor((elapsed % 60000) / 1000);

    const formattedTime = (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
    timer.innerText = formattedTime;
  }
}

function start(minutes, seconds) {
  const interval = setInterval(() => {
    seconds++;

    if (seconds === 60) {
      minutes++;
      seconds = 0;
    } else if (minutes === 90) {
      seconds--;
      clearInterval(interval);
    }

    timer.innerText = `${minutes < 10 ? "0" : ""}${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
  }, 1000);
}
