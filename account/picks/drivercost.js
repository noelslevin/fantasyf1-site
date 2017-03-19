// The counter for i go up to 1 more than the number of race entries
function UpdateCost() {
  var submitObj = document.getElementById('submitpicks');
  var sum = 0;
  var gn, elem;
  for (i=1; i<21; i++) {
    gn = 'pick'+i;
    elem = document.getElementById(gn);
    hn = 'driverpick'+i;
    elem2 = document.getElementById(hn);
    if (elem.checked == true) { sum += Number(elem2.value); }
  }
  document.getElementById('totalcost').value = sum.toFixed(1);
  if (sum < 45.01) {
  submitObj.disabled = false;
  }
  else {
  submitObj.disabled = true;
  }
}