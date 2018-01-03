`Ярослав Тыченко` первая пракитика по JS
=========================
### Код проверять по ссылке [http://dkab.github.io/jasmine-tests/](тут)
### В табе с № задания


### 1
'''
function sequence(startValue, step) {
  startValue = startValue || 0;
  step = step || 1;
  startValue -= step;
  return function () {   
    return startValue += step;
  }
}
'''

### 2
'''
var take = function(fn, count){
  var arr = [];

  for(var i = 0; i < count; i++){
    arr[i] = fn();
  }

  return arr;
}
'''

### 3
'''
function map(fn, array){
  var resultArray = [];

    for(var i = 0; i < array.length; i++){
     resultArray[i] = fn(array[i]);
    }

  return resultArray;
}
'''

### 4
'''
var fmap = function(fn, gen){
  var resultArguments = 0;
  var result = 0;
  var generation = function(){
    var arr = [];

    for(var i = 0; i < arguments.length; i++){
     arr[i] = arguments[i];
    }

    resultArguments = gen.apply(null, arr);
    result = fn(resultArguments);
    return result;
  }
  return generation;
}
'''

### 5
'''
function partial(fn, ...partialArgs) {
  return function(...args) {
    return fn.apply(this, partialArgs.concat(args));
  }
}
'''

### 6 
Тут я не уверен, но тесты проходит.
'''
function partialAny(func) {
  var outerArgs = Array.prototype.slice.call(arguments, 1);  
  return function () {
    var resultArgs = [];
    var j = 0;
    var innerArgs = arguments;

    for (var i = 0; i < outerArgs.length; i++) {
	var outerArg = outerArgs[i];

	if (typeof outerArg === 'undefined') {
	  var innerArg = innerArgs[j++];
          resultArgs.push(innerArg);
        } else {
	   resultArgs.push(outerArg);
        }

    }

    while (j < innerArgs.length) {
	var innerArg = innerArgs[j++];
	resultArgs.push(innerArg);
    }

  return func.apply(this, resultArgs);
  };
}
'''

### 7
'''
function bind(func, context) {
  var bindArgs = [].slice.call(arguments, 2); 
    function wrapper() {                        
      var args = [].slice.call(arguments);
      var unshiftArgs = bindArgs.concat(args);  
      return func.apply(context, unshiftArgs);  
    }
  return wrapper;
}
'''
Пример скрысил тут [https://learn.javascript.ru/bind](learn.javascript) в разделе про биндинг 
Сам бы делал так:
'''
function bind(fn, context) {
  return function() {
    return fn.apply(context, arguments);
  };
}
'''

### 8

function pluck(objects, fieldName) {
  var resultArr = [];

    for (var i = 0; i < objects.length; i++) {
      for (var key in objects[i])
      if (key === fieldName) resultArr[i] = objects[i][key]; 
     }
 
  return resultArr;
}


### 9
```
function filter (arr, fn) {
  var resArr = [];

    for (var i = 0; i < arr.length; i++) {
      if ( fn(arr[i]) ) resArr.push(arr[i]);
    }

  return resArr;
}
```

### 10 
```
function count (obj) {
  var count = 0; 

  for (var keys in obj ) count++;

  return count;
}

```