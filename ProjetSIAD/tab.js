let utilisateurs = [];

function ajouterUtilisateur() {
  const id = document.getElementById('idUtilisateur').value;
  const nom = document.getElementById('nomUtilisateur').value;
  const prenom = document.getElementById('prenomUtilisateur').value;
  const role = document.getElementById('roleUtilisateur').value;
  const Username = document.getElementById('Username').value;
  const Password = document.getElementById('Password').value;
  
  utilisateurs.push({ id, nom, prenom, role, Username, Password });
  afficherUtilisateurs();
  alert("Utilisateur ajouté");

}

function afficherUtilisateurs() {
  const tbody = document.getElementById('tableauUtilisateurs').getElementsByTagName('tbody')[0];
  tbody.innerHTML = ''; // Réinitialiser le tableau

  utilisateurs.forEach((utilisateur, index) => {
    const row = tbody.insertRow();
    row.innerHTML = `
      <td>${utilisateur.id}</td>
      <td>${utilisateur.nom}</td>
      <td>${utilisateur.prenom}</td>
      <td>${utilisateur.role}</td>
      <td>${utilisateur.Username}</td>
      <td>${utilisateur.Password}</td>
      <td>
        <button onclick="modifierUtilisateur(${index})">Modifier</button>
        <button onclick="supprimerUtilisateur(${index})">Supprimer</button>
      </td>
    `;
  });
 
}

function modifierUtilisateur(index) {
  const utilisateur = utilisateurs[index];
  document.getElementById('idUtilisateur').value = utilisateur.id;
  document.getElementById('nomUtilisateur').value = utilisateur.nom;
  document.getElementById('prenomUtilisateur').value = utilisateur.prenom;
  document.getElementById('roleUtilisateur').value = utilisateur.role;
  document.getElementById('Username').value = utilisateur.Username;
  document.getElementById('Password').value = utilisateur.Password;
 
  utilisateurs.splice(index, 1);
  afficherUtilisateurs();
  alert("Utilisateur modofié");
}

function supprimerUtilisateur(index) {
  utilisateurs.splice(index, 1);
  afficherUtilisateurs();
  alert("Utilisateur Supprimé");
}