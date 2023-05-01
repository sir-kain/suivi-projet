/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import {Notyf} from "notyf";
import "notyf/notyf.min.css";

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

// start the Stimulus application
import "./bootstrap";

import "./modal";

window.notyf = new Notyf();
const actions = [...document.querySelectorAll("[name='vente']")].map($form => $form.action);

document.addEventListener("submit", async (e) => {
  const form = e.target;
  const action = form.action;
  if (actions.includes(action)) {
    e.preventDefault();
    const response = await fetch(action, {
      method: "post",
      body: new FormData(form),
    });
    if (!response.ok) {
      notyf.error("Une erreur est survenue.");
      return;
    }
    if (response.redirected) {
      notyf.success("La vente a ete enregistree.");
      window.location.href = response.url;
    } else {
      form.outerHTML = await response.text();
    }
  }
});

const $modalOpenerBtn = document.querySelectorAll(
  '[data-target="modal-edit-vente"][data-vente-id]'
);

$modalOpenerBtn.forEach(($modalOpener) => {
  $modalOpener.addEventListener("click", async (e) => {
    const { venteId } = $modalOpener.dataset;
    //get the form
    const response = await fetch(`/vente/${venteId}/edit`);
    const $form = await response.text();

    if (!response.ok) {
      notyf.error("Une erreur est survenue.");
      return;
    }

    document
      .querySelectorAll("#modal-edit-vente article form")
      .forEach(($form) => {
        $form.remove();
      });
    document
      .querySelector("#modal-edit-vente article")
      .insertAdjacentHTML("beforeend", $form);
  });
});
