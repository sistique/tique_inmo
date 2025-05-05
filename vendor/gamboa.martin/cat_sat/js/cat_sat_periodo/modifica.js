let sl_cat_sat_tipo_producto = $("#cat_sat_tipo_producto_id");
let sl_cat_sat_division_producto = $("#cat_sat_division_producto_id");
let sl_cat_sat_grupo_producto = $("#cat_sat_grupo_producto_id");
let sl_cat_sat_clase_producto = $("#cat_sat_clase_producto_id");
let text_codigo = $("#codigo");

let clase = sl_cat_sat_clase_producto.find('option:selected');
let codigo_clase = clase.data(`cat_sat_clase_producto_codigo`);
