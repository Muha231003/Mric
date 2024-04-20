/**
 * Script for shake animation
 */

Array.from(document.getElementsByClassName("shake-trigger")).forEach((element) => {
  element.addEventListener("mouseover", () => {
    Array.from(document.getElementsByClassName("shake-me-please")).forEach((target) => {
      target.classList.toggle("im-shaking");
    });
  });

  element.addEventListener("mouseout", () => {
    Array.from(document.getElementsByClassName("shake-me-please")).forEach((target) => {
      target.classList.toggle("im-shaking");
    });
  });
});
