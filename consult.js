const selectForm = document.getElementById("filter-form");
const filterModal = new bootstrap.Modal(document.getElementById("openFilterModal"));
var welcomeScreen = document.getElementById("welcome")
var btnReturn2 = document.getElementById("btn-retornar2")

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
  
    if (ScheduleTable.style.display = 'block') {
      document.getElementById('nav-tab').style.display = 'flex'
      loading.hide();
      filterModal.hide();
      }
  });
  

  btnReturn2.addEventListener("click",() => {
    $('#Schedules').DataTable().destroy();
    document.querySelectorAll("div.container-xxl")[2].style.display = 'none'
    welcomeScreen.style.display = 'block'
    document.getElementById('nav-tab').style.display = 'none'
    
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
  