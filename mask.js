function inputHandler(masks, max, event) {
	var c = event.target;
	var v = c.value.replace(/\D/g, '');
	var m = c.value.length > max ? 1 : 0;
	VMasker(c).unMask();
	VMasker(c).maskPattern(masks[m]);
	c.value = VMasker.toPattern(v, masks[m]);
}

var telMask = ['(99) 9999-99999', '(99) 99999-9999'];
var tel = document.getElementById("contato");
var telEdit = document.getElementById("contatoEdit");

VMasker(tel).maskPattern(telMask[1]);
VMasker(telEdit).maskPattern(telMask[1]);


VMasker(document.getElementById("cpf")).maskPattern('999.999.999-99');
VMasker(document.getElementById("cpfEdit")).maskPattern('999.999.999-99');
VMasker(document.getElementById("rg")).maskNumber();
VMasker(document.getElementById("rgEdit")).maskNumber();
VMasker(document.getElementById("dt_nascimento")).maskPattern('99/99/9999');
VMasker(document.getElementById("dataScheduleEdit")).maskPattern('99-99-9999');



/*
var docMask = ['999.999.999-999', '99.999.999/9999-99'];
var doc = document.querySelector('#doc');
VMasker(doc).maskPattern(docMask[0]);
doc.addEventListener('input', inputHandler.bind(undefined, docMask, 14), false);
*/