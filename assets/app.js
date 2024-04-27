import "./styles/app.scss";

// Close all Bootstrap alerts after 3s
Array.from(document.getElementsByClassName("alert")).forEach((element) => {
  setTimeout(() => {
    element.querySelector(".btn-close").click();
  }, 3000);
});
