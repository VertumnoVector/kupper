//forms
const addForm = document.getElementById("add-user-form");
const addScheduleForm = document.getElementById("add-schedule-form");
const addEmployeeForm = document.getElementById("add-employee-form");
const updateForm = document.getElementById("edit-user-form");
const updateScheduleForm = document.getElementById("edit-schedule-form");
const updateEmployeeForm = document.getElementById("edit-employee-form");
const deleteForm = document.getElementById("delete-form");
const deleteScheduleForm = document.getElementById("deleteSchedule-form");
const deleteEmployeeForm = document.getElementById("deleteEmployee-form");
const selectForm = document.getElementById("filter-form");
const showAlert = document.querySelectorAll("[id^='showAlert']");
//modais
const addModal = new bootstrap.Modal(document.getElementById("addNewUserModal"));
const addScheduleModal = new bootstrap.Modal(document.getElementById("addNewScheduleModal"));
const addEmployeeModal = new bootstrap.Modal(document.getElementById("addNewEmployeeModal"));
const editPacientModal = new bootstrap.Modal(document.getElementById("editPacientModal"));
const editScheduleModal = new bootstrap.Modal(document.getElementById("editScheduleModal"));
const editEmployeeModal = new bootstrap.Modal(document.getElementById("editEmployeeModal"));
const confirmDeleteModal = new bootstrap.Modal(document.getElementById("confirmDeleteModal"));
const confirmDeleteScheduleModal = new bootstrap.Modal(document.getElementById("confirmDeleteScheduleModal"));
const confirmDeleteEmployeeModal = new bootstrap.Modal(document.getElementById("confirmDeleteEmployeeModal"));
const filterModal = new bootstrap.Modal(document.getElementById("openFilterModal"));
const conflictModal = new bootstrap.Modal(document.getElementById("conflictModal"));


const tbody = document.querySelector("tbody")
var schedules = document.getElementById("Schedules")
var welcomeScreen = document.getElementById("welcome")
var btnReturn = document.getElementById("btn-retornar")
var btnReturn2 = document.getElementById("btn-retornar2")
var btnReturn3 = document.getElementById("btn-retornar3")


// CREATE CLIENTE
addForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(addForm);
  formData.append("add", 1);

  if (addForm.checkValidity() === false) {
    e.preventDefault();
    e.stopPropagation();
    addForm.classList.add("was-validated");
    return false;
  } else {
    document.getElementById("add-user-btn").value = "Please Wait...";

    const data = await fetch("action.php", {
      method: "POST",
      body: formData,
    });
    const response = await data.text();
    showAlert[1].innerHTML = response;
    document.getElementById("add-user-btn").value = "Incluir venda";
    addForm.reset();
    addForm.classList.remove("was-validated");
    addModal.hide();
    //fetchAllUsers();
    $(showAlert[1]).fadeTo(2000, 500).slideUp(500, function(){
      $(showAlert[1]).slideUp(500);
    });
  }
});


// GET CLIENTES
const fetchAllUsers = async () => {
  let PacientTableTitle = document.getElementById("PacientTableTitle")
  let pacientTable = document.getElementById("pacientTable")
  let EmployeeTable = document.getElementById("employeeTable")
  
  PacientTableTitle.textContent = 'PACIENTES CADASTRADOS'
  welcomeScreen.style.display = 'none'

  const data = await fetch("action.php?read=1", {
    method: "GET",
  });
  const response = await data.text();
  document.getElementById("pacients").innerHTML = response;
  pacientTable.style.display = 'block'
  if ($('#pacientsTable').DataTable()){
    $('#pacientsTable').DataTable().destroy()
  }
  $('#pacientsTable').DataTable();

  if (EmployeeTable.style.display = 'block') {
    EmployeeTable.style.display = 'none'
  }
  if (ScheduleTable.style.display = 'block') {
    ScheduleTable.style.display = 'none'
  }


};


// Edit User Ajax Request
tbody.addEventListener("click", (e) => {
  if (e.target && e.target.matches("a.editLink")) {
    e.preventDefault();
    let id = e.target.getAttribute("id");
    editUser(id);
  }
});

const editUser = async (id) => {
  const data = await fetch(`action.php?edit=1&id=${id}`, {
    method: "GET",
  });
  const response = await data.json();
  document.getElementById("idEdit").value = response.id;
  document.getElementById("nomeEdit").value = response.nome;
  //document.getElementById("planoEdit").value = response.plano;
  document.getElementById("cpfEdit").value = response.cpf;
  document.getElementById("rgEdit").value = response.rg;
  document.getElementById("enderecoEdit").value = response.endereco;
  document.getElementById("contatoEdit").value = response.contato;
  document.getElementById("diaSemanaEdit").value = response.diasemana;
  document.getElementById("horaEdit").value = response.hora;
};

// UPDATE
updateForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(updateForm);
  formData.append("update", 1);

  if (updateForm.checkValidity() === false) {
    e.preventDefault();
    e.stopPropagation();
    updateForm.classList.add("was-validated");
    return false;
  } else {
    document.getElementById("edit-user-btn").value = "Please Wait...";

    const data = await fetch("action.php", {
      method: "POST",
      body: formData,
    });
    const response = await data.text();

    showAlert[1].innerHTML = response;
    document.getElementById("edit-user-btn").value = "Atualizar regitro";
    updateForm.reset();
    updateForm.classList.remove("was-validated");
    editPacientModal.hide();
   // fetchAllUsers();
   $(showAlert[1]).fadeTo(2000, 500).slideUp(500, function(){
    $(showAlert[1]).slideUp(500);
  });
  }
});

// Delete User Ajax Request
tbody.addEventListener("click", (e) => {
  if (e.target && e.target.matches("a.deleteLink")) {
    e.preventDefault();
    const id = e.target.getAttribute("id")

    confirmDeleteModal.show(
      deleteForm.addEventListener("submit", async (e) =>{
        e.preventDefault()
        deleteUser(id)
        confirmDeleteModal.hide()
      })
    )
  }
})


const deleteUser = async (id) => {
  const data = await fetch(`action.php?delete=1&id=${id}`, {
    method: "DELETE",
  });
  const response = await data.text();
  showAlert[0].innerHTML = response;
  fetchAllUsers();
  $(showAlert[0]).fadeTo(2000, 500).slideUp(500, function(){
    $(showAlert[0]).slideUp(500);
  });
};

//SCHEDULES

const fetchPacients = async () => {
  const data = await fetch("action.php?readPacients=1", {
    method: "GET",
  });
  const response = await data.text();
  let defaultOption = "<option value='' disabled selected >Selecione...</option>";
  nomesAtendimento.innerHTML = defaultOption.concat(response);

  var select_box_element = document.getElementById('nomesAtendimento');

  dselect(select_box_element, {
      search: true
  });
};

 const getPacientData = async ()=> {
  let id = document.getElementById("nomesAtendimento").value
  const data = await fetch(`action.php?getPacientData=1&id=${id}`, {
    method: "GET",
  });
  const response = await data.text();

  let selectedName = response.split(".")
  //console.log(selectedName)
  const diaSemana = selectedName[0]
  const hora = selectedName[1]
  document.getElementById('diasemanaatendimento').value = diaSemana;
  document.getElementById('diasemanaatendimento').disabled = false;
  document.getElementById('horaatendimento').value = hora;
  document.getElementById('horaatendimento').disabled = false;
  document.getElementById('especialidadeatendimento').disabled = false;
  

 };

 const getProfissionalData = async ()=>{
  const data = await fetch("action.php?getProfissionalData=1", {
    method: "GET",
  });
  const response = await data.text();
  let profissional = response.replace('"','').split(',')
  let registros =  document.getElementById('especialidadeatendimento')
  $("#especialidadeatendimento").empty();
  for (var i = 0; i <= profissional.length-2; i++){
    var opt = document.createElement('option');
    opt.value = profissional[i];
    opt.innerHTML = profissional[i];
    registros.appendChild(opt);
  }
 }



//CREATE
addScheduleForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formDataSchedule = new FormData(addScheduleForm);
  formDataSchedule.append("addSchedule", 1);

  if (addScheduleForm.checkValidity() === false) {
    e.preventDefault();
    e.stopPropagation();
    addScheduleForm.classList.add("was-validated");
    return false;
  } else {
    document.getElementById("add-schedule-btn").value = "Please Wait...";

    const data = await fetch("action.php", {
      method: "POST",
      body: formDataSchedule,
    });
    const response = await data.text();
    showAlert[0].innerHTML = response;

    document.getElementById("add-schedule-btn").value = "Incluir atendimento";
    addScheduleForm.reset();
    addScheduleForm.classList.remove("was-validated");
    addScheduleModal.hide();
    //fetchAllSchedule();

    $(showAlert[0]).fadeTo(2000, 500).slideUp(500, function(){
      $(showAlert[0]).slideUp(500);
    });
    if (!response.includes("sucesso")){
      conflictModal.show();
    }
  }
});

//GET ALL SCHEDULES
const fetchAllSchedule = async () => {
  const loading = new bootstrap.Modal(document.getElementById('loading'), {
    keyboard: false,
    focus: true,
    animation: true
  })

  let EmployeeTable = document.getElementById("employeeTable")
  let ScheduleTableTitle = document.getElementById("ScheduleTableTitle")
  let ScheduleTable = document.getElementById("ScheduleTable")
  let scheduleContent = document.getElementById("scheduleContent")
  

  ScheduleTableTitle.textContent = 'ATENDIMENTOS CADASTRADOS'
  welcomeScreen.style.display = 'none'
  loading.show();


  const data = await fetch("action.php?readSchedule=1", {
    method: "GET",
  });

  const calendar = await fetch("action.php?readSchedule2=1", {
    method: "GET",
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
  }
  });

  if ($('#Schedules').DataTable()){
    $('#Schedules').DataTable().destroy()
  }

  const response = await data.text();
  var response2 = await calendar.json()

  var evt = JSON.parse(response2)
  var myEvents = []
  for (let i = 0; i < evt.length; i++) {
    myEvents.push(
      { 
          id: evt[i].tipo,
          name: evt[i].medico, 
          description: evt[i].tipo+' - '+'Paciente: '+evt[i].nome+' - '+evt[i].dataconsulta.slice(10,16)+'🕒',
          date: evt[i].dataconsulta,
          badge: evt[i].estado,
          type: "event", 
      }
    )
  }
  
  
  $('#calendar').evoCalendar({
    theme: 'Midnight Blue',
    language: 'pt',
    calendarEvents: myEvents
})


  scheduleContent.innerHTML = response;
  $('#Schedules').DataTable()
  ScheduleTable.style.display = 'block'


  if (pacientTable.style.display = 'block') {
    pacientTable.style.display = 'none'
    document.getElementById('nav-tab').style.display = 'flex'
  }
  if (EmployeeTable.style.display = 'block') {
    EmployeeTable.style.display = 'none'
  }
  if (ScheduleTable.style.display = 'block') {
		loading.hide();
	}


};

// GET SCHEDULES BY PROFESSIONAL
selectForm.addEventListener("submit", async (e) =>{

  e.preventDefault();

  const loading = new bootstrap.Modal(document.getElementById('loading'), {
    keyboard: false,
    focus: true,
    animation: true
  })


  let EmployeeTable = document.getElementById("employeeTable")
  let ScheduleTableTitle = document.getElementById("ScheduleTableTitle")
  let ScheduleTable = document.getElementById("ScheduleTable")
  let scheduleContent = document.getElementById("scheduleContent")

  ScheduleTableTitle.textContent = 'ATENDIMENTOS CADASTRADOS'
  welcomeScreen.style.display = 'none'
  loading.show();

 categoriaFilter = document.getElementById("categoriaFilter").value;
 ScheduleTableTitle.textContent = 'ATENDIMENTOS CADASTRADOS PARA: '+categoriaFilter

  const data = await fetch(`action.php?readScheduleCat=1&select=${categoriaFilter}`, {
    method: "GET",
    
  });
  const calendar = await fetch(`action.php?readScheduleCat2=1&select=${categoriaFilter}`, {
    method: "GET",
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
  }
  });

  if ($('#Schedules').DataTable()){
    $('#Schedules').DataTable().destroy()
  }

  const response = await data.text();
  var response2 = await calendar.json()

  var evt = JSON.parse(response2)
  var myEvents = []
  for (let i = 0; i < evt.length; i++) {
    myEvents.push(
      { 
          id: evt[i].tipo,
          name: evt[i].medico, 
          description: evt[i].tipo+' - '+'Paciente: '+evt[i].nome+' - '+evt[i].dataconsulta.slice(10,16)+'🕒',
          date: evt[i].dataconsulta,
          badge: evt[i].estado,
          type: "event", 
      }
    )
  }
  
  
  $('#calendar').evoCalendar({
    theme: 'Midnight Blue',
    language: 'pt',
    calendarEvents: myEvents
})


  scheduleContent.innerHTML = response;
  $('#Schedules').DataTable()
  ScheduleTable.style.display = 'block'


  if (pacientTable.style.display = 'block') {
    pacientTable.style.display = 'none'
    document.getElementById('nav-tab').style.display = 'flex'
  }
  if (EmployeeTable.style.display = 'block') {
    EmployeeTable.style.display = 'none'
  }
  if (ScheduleTable.style.display = 'block') {
		loading.hide();
    filterModal.hide();
	}
});


//UPDATE
scheduleContent.addEventListener("click", (e) => {
  if (e.target && e.target.matches("a.editLink")) {
    e.preventDefault();
    let id = e.target.getAttribute("id");
    editSchedule(id);
  }
});

const editSchedule = async (id) => {
  const data = await fetch(`action.php?editSchedule=1&id=${id}`, {
    method: "GET",
  });
  const response = await data.json();
  document.getElementById("idScheduleEdit").value = response.idatendimento;
  document.getElementById("nomeScheduleEdit").value = response.nome;
  document.getElementById("contatoScheduleEdit").value = response.contato;
};


updateScheduleForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(updateScheduleForm);
  formData.append("updateSchedule", 1);

  if (updateScheduleForm.checkValidity() === false) {
    e.preventDefault();
    e.stopPropagation();
    updateScheduleForm.classList.add("was-validated");
    return false;
  } else {
    document.getElementById("edit-schedule-btn").value = "Please Wait...";

    const data = await fetch("action.php", {
      method: "POST",
      body: formData,
    });
    const response = await data.text();
    showAlert[0].innerHTML = response;
    document.getElementById("edit-schedule-btn").value = "Atualizar Registro";
    updateScheduleForm.reset();
    updateScheduleForm.classList.remove("was-validated");
    editScheduleModal.hide();
    fetchAllSchedule();
    $(showAlert[0]).fadeTo(2000, 500).slideUp(500, function(){
      $(showAlert[0]).slideUp(500);
    });
  }
});

document.getElementById("scheduleContent").addEventListener("click",(e) =>{
  if (e.target && e.target.matches("input.frequencia")) {
    e.preventDefault();
    let id = e.target.getAttribute("id");
    updateScheduleFrequence(id);
  }

  if (e.target && e.target.matches(".registerFault")) {
    e.preventDefault();
    let id = e.target.getAttribute("id");
    registerFault(id);
  }
})

const updateScheduleFrequence = async (id) => {
  const data = await fetch(`action.php?editScheduleFrequence=1&id=${id}`, {
    method: "POST",
  });

  const response = await data.text();
  showAlert[0].innerHTML = response;

  //document.getElementsByClassName("frequencia").checked = true

  $(showAlert[0]).fadeTo(2000, 500).slideUp(500, function(){
    $(showAlert[0]).slideUp(500);
  });
  fetchAllSchedule();
};

const registerFault = async (id) => {
  const data = await fetch(`action.php?registerFault=1&id=${id}`, {
    method: "POST",
  });

  const response = await data.text();
  showAlert[0].innerHTML = response;

  $(showAlert[0]).fadeTo(2000, 500).slideUp(500, function(){
    $(showAlert[0]).slideUp(500);
  });
  fetchAllSchedule();
};


// Delete schedule Ajax Request
document.getElementById("scheduleContent").addEventListener("click", (e) => {
  if (e.target && e.target.matches("a.deleteScheduleLink")) {
    e.preventDefault();
    const idatendimento = e.target.getAttribute("Id")
    confirmDeleteScheduleModal.show(
      deleteScheduleForm.addEventListener("submit", async (e) =>{
        e.preventDefault()
        deleteSchedule(idatendimento)
        confirmDeleteScheduleModal.hide()
      })
    )
  }
})


const deleteSchedule = async (id) => {
  const data = await fetch(`action.php?deleteSchedule=1&id=${id}`, {
    method: "DELETE",
  });
  const response = await data.text();
  showAlert[0].innerHTML = response;
  

  
  fetchAllSchedule();
  $(showAlert[0]).fadeTo(2000, 500).slideUp(500, function(){
    $(showAlert[0]).slideUp(500);

  });
};


//EMPLOYEES
//CREATE
addEmployeeForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(addEmployeeForm);
  formData.append("addEmployee", 1);

  if (addEmployeeForm.checkValidity() === false) {
    e.preventDefault();
    e.stopPropagation();
    addEmployeeForm.classList.add("was-validated");
    return false;
  } else {
    document.getElementById("add-employee-btn").value = "Please Wait...";

    const data = await fetch("action.php", {
      method: "POST",
      body: formData,
    });
    const response = await data.text();
    showAlert[0].innerHTML = response;
    document.getElementById("add-employee-btn").value = "Incluir profissional";
    addEmployeeForm.reset();
    addEmployeeForm.classList.remove("was-validated");
    addEmployeeModal.hide();
    fetchAllEmployees();

    $(showAlert[0]).fadeTo(2000, 500).slideUp(500, function(){
      $(showAlert[0]).slideUp(500);
    });
  }
});


//READ
const fetchAllEmployees = async () => {
  let EmployeeTableTitle = document.getElementById("employeeTableTitle")
  let EmployeeTable = document.getElementById("employeeTable")
  let EmployeeContent = document.getElementById("employeeContent")
  
  EmployeeTableTitle.textContent = 'PROFISSIONAIS CADASTRADOS'
  welcomeScreen.style.display = 'none'

  const data = await fetch("action.php?readEmployee=1", {
    method: "GET",
  });
  const response = await data.text();
  EmployeeContent.innerHTML = response;
  EmployeeTable.style.display = 'block'
  if ($('#employees').DataTable()){
    $('#employees').DataTable().destroy()
  }
  $('#employees').DataTable()
  


  if (ScheduleTable.style.display = 'block') {
    ScheduleTable.style.display = 'none'
  }
  if (pacientTable.style.display = 'block') {
    pacientTable.style.display = 'none'
  }

};


//UPDATE
document.getElementById("employeeContent").addEventListener("click", (e) => {
  if (e.target && e.target.matches("a.editLink")) {
    e.preventDefault();
    let id = e.target.getAttribute("id");
    editEmployee(id);
  }
});

const editEmployee = async (id) => {
  const data = await fetch(`action.php?editEmployee=1&id=${id}`, {
    method: "GET",
  });
  const response = await data.json();
  document.getElementById("idEmployeeEdit").value = response.id;
  document.getElementById("nomeEmployeeEdit").value = response.nome;
  document.getElementById("categoriaEdit").value = response.cargo;
};


updateEmployeeForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(updateEmployeeForm);
  formData.append("updateEmployee", 1);

  if (updateEmployeeForm.checkValidity() === false) {
    e.preventDefault();
    e.stopPropagation();
    updateEmployeeForm.classList.add("was-validated");
    return false;
  } else {
    document.getElementById("edit-employee-btn").value = "Please Wait...";

    const data = await fetch("action.php", {
      method: "POST",
      body: formData,
    });
    const response = await data.text();

    showAlert[0].innerHTML = response;
    document.getElementById("edit-employee-btn").value = "Atualizar Registro";
    updateEmployeeForm.reset();
    updateEmployeeForm.classList.remove("was-validated");
    editEmployeeModal.hide();
    fetchAllEmployees();
    $(showAlert[0]).fadeTo(2000, 500).slideUp(500, function(){
      $(showAlert[0]).slideUp(500);
    });
  }
});



// DELETE
document.getElementById("employeeContent").addEventListener("click", (e) => {
  if (e.target && e.target.matches("a.deleteEmployeeLink")) {
    e.preventDefault();
    const idEmployee = e.target.getAttribute("id")
    confirmDeleteEmployeeModal.show(
      deleteEmployeeForm.addEventListener("submit", async (e) =>{
        e.preventDefault()
        deleteEmployee(idEmployee)
        confirmDeleteScheduleModal.hide()
      })
    )
  }

})
const deleteEmployee = async (id) => {
  const data = await fetch(`action.php?deleteEmployee=1&id=${id}`, {
    method: "DELETE",
  });
  const response = await data.text();
  showAlert[0].innerHTML = response;
  
  fetchAllEmployees();
  $(showAlert[0]).fadeTo(2000, 500).slideUp(500, function(){
    $(showAlert[0]).slideUp(500);
  });
};



btnReturn.addEventListener("click",() => {
  $('#pacientsTable').DataTable().destroy();
  document.querySelectorAll("div.container-xxl")[2].style.display = 'none'
  welcomeScreen.style.display = 'block'
})
btnReturn2.addEventListener("click",() => {
  $('#Schedules').DataTable().destroy();
  document.querySelectorAll("div.container-xxl")[4].style.display = 'none'
  welcomeScreen.style.display = 'block'
  document.getElementById('nav-tab').style.display = 'none'
  
})
btnReturn3.addEventListener("click",() => {
  $('#employees').DataTable().destroy();
  document.querySelectorAll("div.container-xxl")[7].style.display = 'none'
  welcomeScreen.style.display = 'block'
})






// GET TODAY
const fetchToday = async () => {
  let today = new Date();
  let dd = String(today.getDate()).padStart(2, '0');
  let mm = String(today.getMonth() + 1).padStart(2, '0');
  let yyyy = today.getFullYear();
  today = dd + '/' + mm + '/' + yyyy;

  let todayTableTitle = document.getElementById("todayTableTitle")
  let todayContent = document.getElementById("todayContent")
  
  todayTableTitle.textContent = 'ATENDIMENTOS PARA HOJE: '+today

  const data = await fetch("action.php?readToday=1", {
    method: "GET",
  });
  const response = await data.text();
  todayContent.innerHTML = response;
  if ($('#today').DataTable()){
    $('#today').DataTable().destroy()
  }
  $('#today').DataTable()


};
fetchToday();








