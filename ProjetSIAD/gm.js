let machines = [];

function ajouterMachine() {
  const id = document.getElementById('idMachine').value;
  const type = document.getElementById('type').value;
  const etat = document.getElementById('etatactual').value;
  const dateMaintenance = document.getElementById('datemintenance').value;

  
  machines.push({ id, type, etat, dateMaintenance });
  afficherMachines();
  alert("Machine ajoutée");
}

function afficherMachines() {
  const tbody = document.getElementById('tableauMachines').getElementsByTagName('tbody')[0];
  tbody.innerHTML = ''; 

  machines.forEach((machine, index) => {
    const row = tbody.insertRow();
    row.innerHTML = `
      <td>${machine.id}</td>
      <td>${machine.type}</td>
      <td>${machine.etat}</td>
      <td>${machine.dateMaintenance}</td>
      <td>
        <button onclick="modifierMachine(${index})">Modifier</button>
        <button onclick="supprimerMachine(${index})">Supprimer</button>
      </td>
    `;
  });
}

function modifierMachine(index) {
  const machine = machines[index];
  document.getElementById('idMachine').value = machine.id;
  document.getElementById('typeMachine').value = machine.type;
  document.getElementById('etatMachine').value = machine.etat;
  document.getElementById('dateMaintenance').value = machine.dateMaintenance;

  
  machines.splice(index, 1);
  afficherMachines();
  alert("Machine modifiée");
}

function supprimerMachine(index) {
  machines.splice(index, 1);
  afficherMachines();
  alert("Machine supprimée");
}