<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Practice 13</title>

        <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://rawgit.com/wenzhixin/bootstrap-table/master/src/bootstrap-table.css">
   
    <style>
        .tasks {
            padding: 20px;
            padding-left: 40px;
        }
        .loader {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background-color: #000;
            border-radius: 5px;
            opacity: 0.3;
            text-align: center;
            padding: 20px;
            min-height: 140px;
        }
    </style>
    
</head>
<body>
<div class="tasks">
<h4>
Данные для таблицы должны подтягиваться по ajax по url <code>ajax.php</code>.
Номенр текущей страницы должен быть чаcтью url (<i class="text-info">/page1</i>, <i class="text-info">/page2</i>...).<br />
Дожна быть возможность сортировки по всем с толбцам, параметры сортировки записывать как GET параметры (<i class="text-info">sort</i> и <i class="text-info">dir</i>).
Если приложение открыто в нескольких вкладках - то при изменении сортировки - она должна поменяться во всех вкладках.<br />
Когда сайт открывается первый раз (по <a href="http://main.hz/">ссылке</a>) - то в адресной строке должно отображаться <code>http://main.hz/page1</code>,
при этом данный адресс должен быть первым в истории посещений.<br />
Во время загрузки данных нужно отображать лоадер, а потом его скрывать.
</h4>
<br />

<div class="bootstrap-table">
    <div class="fixed-table-container">
        <div class="loader"><img src="https://i.giphy.com/Mf9o6Z2CNs1eE.gif" height="100" hidden></div>
        <table id="example" class="table table-striped table-bordered dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;">
            <thead>
                <tr class="info">
                    <th data-field="id">
                        <div class="th-inner sortable both">
                            ID
                        </div>
                    </th>
                    <th data-field="name">
                        <div class="th-inner sortable both">
                            Марка
                        </div>
                    </th>
                    <th data-field="year">
                        <div class="th-inner sortable both">
                            Год выпуска
                        </div>
                    </th>
                    <th data-field="color">
                        <div class="th-inner sortable both">
                            Цвет
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
               <!--  <tr>
                   <td>1</td>
                   <td>Renault Sandero</td>
                   <td>2012</td>
                   <td>Красный</td>
               </tr>
               <tr>
                   <td>2</td>
                   <td>Renault Stepway</td>
                   <td>2015</td>
                   <td>Синий</td>
               </tr> -->
           
        </table>
    </div>
</div>
<div class="pull-right pagination">
    <ul class="pagination">
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">4</a></li>
        <li class="page-item"><a class="page-link" href="#">5</a></li>
    </ul>
</div>

<script>

const gif = document.getElementsByClassName(`loader`)[0];
const tbody = document.getElementsByTagName(`tbody`)[0];
let pageItems = document.getElementsByClassName(`page-item`);
let sortItems = document.getElementsByTagName(`th`);

//обзервер на изменение данных в localstorage, для "синхронности" вкладок
window.addEventListener('storage', function(e) {

   changeSort( localStorage.getItem("sort"), localStorage.getItem("dir") );
   changePage( localStorage.getItem("page") ); 
   showGIF(gif);  
   makeXhr();   
   changeURL();
   changePagination();  
   changeSortArrow();
});
 
// проверка при пустом localstorage
if (!localStorage.getItem("sort")) localStorage.setItem("sort", "id");
if (!localStorage.getItem("page")) localStorage.setItem("page", "1");
if (!localStorage.getItem("dir")) localStorage.setItem("dir", "asc");

// стартовый переход на страницу, ещё без действий
history.replaceState({page: localStorage.getItem("page")}, null, `page${localStorage.getItem("page")}`);
showGIF(gif);
makeXhr();
document.getElementsByClassName('page-item')[localStorage.getItem("page") - 1].classList.add('active');
changeSortArrow();

//показать машинки и закинуть значения в таблицу. Вставлять кусьмяру html сразу,— вроде как самый производительный способ
function showCars(cars) {
let tbodyHTML = ``;

cars.forEach(function(cars) {
tbodyHTML += `<tr>
                <td>${cars.id}</td>
                <td>${cars.name}</td>
                <td>${cars.year}</td>
                <td>${cars.color}</td>
              </tr>`;
 });

tbody.innerHTML = tbodyHTML;
}

function changeURL(){
history.pushState({page: localStorage.getItem("page")} , null, `page${localStorage.getItem("page")}`);
}

// изменяет значения страницы в localstorage
function changePage(newPage){

  if (localStorage.getItem(`page`) == newPage) return;
  
  localStorage.setItem(`page`, `${newPage}`);
  makeXhr();
  changeURL();
}

// вешаем обработчики на клик для каждой страницы пагинации
for (let i = 0; i < pageItems.length; i++ ){

  pageItems[i].onclick = function(e){  
    event.preventDefault();
    changePage(i+1);
    changePagination();        
  }
}

// вешаем обработчики на клик для каждого столбца сортировки
for (let i = 0; i < sortItems.length; i++ ){

  sortItems[i].onclick = function(e){  
    event.preventDefault();     

    if (localStorage.getItem(`dir`) == `asc` ) {
      changeSort(sortItems[i].dataset.field, `desc`)
    } else {
      changeSort(sortItems[i].dataset.field, `asc`)
    } 

    changeSortArrow();
  }
}

// изменяет значения сортировки и порядка в localstorage
function changeSort(newSort, newDir){

  if (localStorage.getItem(`sort`) == newSort)
    if (localStorage.getItem(`dir`) == newDir) return;
   
  if (localStorage.getItem(`sort`) == newSort){

    if (newDir == 'asc'){
      localStorage.setItem(`dir`, `asc`);
      makeXhr();
      return;
    } 

    if (newDir == 'desc') {
      localStorage.setItem(`dir`, `desc`);     
      makeXhr();
      return;
    }
  }
  localStorage.setItem(`sort`, `${newSort}`);
  localStorage.setItem(`dir`, `${newDir}`);
  makeXhr();  
}

//меняет номер страницы в пагинации
function changePagination() {
for (let j = 0; j < pageItems.length; j++ )
      pageItems[j].classList.remove(`active`)

    pageItems[localStorage.getItem("page") - 1].classList.add(`active`);
}

//меняет стрелку сортировки в таблице
function changeSortArrow(){
   for (let j = 0; j < sortItems.length; j++ ){ 

     if ( sortItems[j].getElementsByClassName(`sortable`)[0].classList.contains(`asc`) ) 
      sortItems[j].getElementsByClassName(`sortable`)[0].classList.remove(`asc`);

     if ( sortItems[j].getElementsByClassName(`sortable`)[0].classList.contains(`desc`) ) 
      sortItems[j].getElementsByClassName(`sortable`)[0].classList.remove(`desc`);
       
     if ( sortItems[j].dataset.field == localStorage.getItem(`sort`) )
      sortItems[j].getElementsByClassName(`sortable`)[0].classList.add(`${localStorage.getItem(`dir`)}`)
   }
}

function showGIF(gif){
  gif.removeAttribute(`hidden`);
}

// настравиаем ИксХаЄр 
function makeXhr(){
var xhr = new XMLHttpRequest();

//выделил парамтеры в переменную только для читабельности
let params = '?page=' + encodeURIComponent(localStorage.getItem("page")) +
             '&sort=' + encodeURIComponent(localStorage.getItem("sort")) +
             '&dir=' + encodeURIComponent(localStorage.getItem("dir"));

xhr.open("GET", 'ajax.php' + params, true);  

xhr.send();

  xhr.onreadystatechange = function() {

    if (xhr.readyState == 4) gif.setAttribute(`hidden`, true);

    if (xhr.readyState != 4) return;

    if (xhr.status != 200) {         
      let cars;
      alert(xhr.status + ': ' + xhr.statusText);     // обработать ошибку
    } else {
          try {
            cars = JSON.parse(xhr.responseText);
          } catch (e) {
                alert( "Что-то пошло не так: " + e.message );
            }
              showCars(cars);
    }    
  }
}
</script>
</div>
<br><br><br>

</body>
</html>




