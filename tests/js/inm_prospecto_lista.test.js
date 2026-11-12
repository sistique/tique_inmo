const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');

const script = fs.readFileSync(path.join(__dirname, '../../js/filtros_lista.js'), 'utf8');

function pagina(storage, usuario = 1, catalogo = 'inm_prospecto') {
    const campos = [
        {id: 'id', type: 'input', value: ''},
        {id: 'nss', type: 'input', value: ''},
        {id: 'nombre_prospecto', type: 'input', value: ''},
        {id: 'ubicacion', type: 'input', value: ''},
        {id: 'fecha_inicio', type: 'input', value: ''},
        {id: 'fecha_fin', type: 'input', value: ''},
        {id: 'inm_status_' + catalogo.slice(4), type: 'select', value: []}
    ];
    const hidden = Object.fromEntries(campos.map(campo => ['hidden_' + campo.id, {value: ''}]));
    const buttons = {
        filtrar: {id: 'filtrar', disabled: false},
        limpiar: {id: 'limpiar', disabled: false}
    };
    const contenedor = {usuario};
    const documento = {getElementById: id => hidden[id]};
    const ventana = {location: {pathname: '/index.php'}};
    const paneles = {
        'split-view': {classes: new Set()},
        'panel-compradores': {classes: new Set()},
        'panel-ubicaciones': {classes: new Set()},
        'split-divisor': {classes: new Set()},
        'tabla-ubicaciones': {classes: new Set()}
    };
    const tabla = {};
    const reloads = [];
    const timers = new Map();
    const alerts = [];
    let timerId = 0;
    const ready = [];

    function collection(elements) {
        return {
            length: elements.length,
            each(fn) {
                for (const element of elements) {
                    if (fn.call(element) === false) break;
                }
                return this;
            },
            val(value) {
                if (arguments.length === 0) return elements[0].value;
                elements.forEach(element => {
                    element.value = element.type === 'select' && value === null ? [] : value;
                });
                return this;
            },
            data(key) {
                return key === 'usuario-id' ? elements[0].usuario : undefined;
            },
            prop(key, value) {
                if (arguments.length === 1) return elements[0][key];
                elements.forEach(element => { element[key] = value; });
                return this;
            },
            on(events, selector, handler) {
                if (typeof selector === 'function') handler = selector;
                elements.forEach(element => {
                    element.handlers ||= {};
                    events.split(' ').forEach(event => {
                        (element.handlers[event] ||= []).push(handler);
                    });
                });
                return this;
            },
            trigger(event) {
                elements.forEach(element => {
                    (element.handlers?.[event] || []).forEach(handler => handler.call(element));
                });
                return this;
            },
            click() {
                return this.trigger('click');
            },
            filter(type) {
                return collection(elements.filter(element => element.type === type));
            },
            select2() {
                return this;
            },
            ready(fn) {
                ready.push(fn);
            },
            hasClass(name) {
                return elements[0].classes.has(name);
            },
            toggleClass(name, enabled) {
                elements.forEach(element => enabled ? element.classes.add(name) : element.classes.delete(name));
                return this;
            },
            css() {
                return this;
            },
            outerWidth() {
                return 1200;
            },
            DataTable(options) {
                if (options) return {columns: {adjust() {}}};
                return {
                    columns: {adjust() {}},
                    responsive: {recalc() {}},
                    ajax: {
                        reload(callback) {
                            reloads.push(Object.fromEntries(campos.map(campo => [campo.id, campo.value])));
                            if (callback) callback();
                        }
                    }
                };
            }
        };
    }

    function $(selector) {
        if (typeof selector !== 'string') return collection([selector]);
        if (selector === '.filtros-avanzados input[data-tipo], .filtros-avanzados select[data-tipo]') {
            return collection(campos);
        }
        if (selector === '.filtros-avanzados') return collection([contenedor]);
        if (selector === '.basic-multiple') return collection(campos.filter(campo => campo.type === 'select'));
        if (selector === '.datatable') return collection([tabla]);
        if (selector === '.split-view .split-panel') return collection([paneles['panel-compradores'], paneles['panel-ubicaciones']]);
        if (selector[0] === '#') {
            const id = selector.slice(1);
            const element = buttons[id] || paneles[id] || campos.find(campo => campo.id === id);
            return collection(element ? [element] : []);
        }
        throw new Error('Selector inesperado: ' + selector);
    }

    const context = vm.createContext({
        $, document: documento, window: ventana, get_url: () => '/ubicaciones',
        localStorage: storage, console: {error() {}}, alert: message => alerts.push(message),
        setTimeout: fn => { timers.set(++timerId, fn); return timerId; },
        clearTimeout: id => timers.delete(id)
    });
    vm.runInContext(script, context);
    const lista = fs.readFileSync(path.join(__dirname, '../../js', catalogo, 'lista.js'), 'utf8');
    vm.runInContext(lista, context);

    const inicial = Object.fromEntries(campos.map(campo => [campo.id, campo.value]));
    ready.forEach(fn => fn());
    return {
        campos: Object.fromEntries(campos.map(campo => [campo.id, campo])),
        hidden, buttons, reloads, inicial, alerts, paneles,
        change(id, value, event = 'change') {
            const campo = campos.find(item => item.id === id);
            $(campo).val(value).trigger(event);
        },
        click(id) { $(buttons[id]).click(); },
        runTimers() {
            const callbacks = [...timers.values()];
            timers.clear();
            callbacks.forEach(callback => callback());
        }
    };
}

function storage() {
    const values = new Map();
    return {
        getItem: key => values.has(key) ? values.get(key) : null,
        setItem: (key, value) => values.set(key, value),
        removeItem: key => values.delete(key)
    };
}

test('restaura los filtros aplicados antes de inicializar la tabla y la exportacion', () => {
    const almacen = storage();
    const primera = pagina(almacen);
    primera.change('id', '42', 'input');
    primera.change('nombre_prospecto', 'JUAN', 'input');
    primera.change('fecha_inicio', '2026-01-01');
    primera.change('inm_status_prospecto', ['NUEVO', 'PROCESO']);
    primera.runTimers();
    primera.change('nombre_prospecto', 'SIN APLICAR', 'input');

    const vuelta = pagina(almacen);
    assert.equal(vuelta.inicial.id, '42');
    assert.equal(vuelta.inicial.nombre_prospecto, 'JUAN');
    assert.equal(vuelta.inicial.fecha_inicio, '2026-01-01');
    assert.deepEqual(Array.from(vuelta.inicial.inm_status_prospecto), ['NUEVO', 'PROCESO']);
    assert.equal(vuelta.hidden.hidden_inm_status_prospecto.value, 'NUEVO,PROCESO');
    assert.equal(vuelta.hidden.hidden_nombre_prospecto.value, 'JUAN');
    assert.equal(vuelta.buttons.limpiar.disabled, false);
    assert.equal(pagina(almacen, 2).inicial.id, '');
});

test('refiltrar reemplaza los valores y limpiar cancela el filtrado pendiente', () => {
    const almacen = storage();
    const primera = pagina(almacen);
    primera.change('nss', '123', 'input');
    primera.click('filtrar');
    primera.change('nss', '', 'input');
    assert.equal(primera.hidden.hidden_nss.value, '');
    primera.click('filtrar');
    assert.equal(pagina(almacen).inicial.nss, '');

    primera.change('inm_status_prospecto', ['NUEVO']);
    primera.click('limpiar');
    primera.runTimers();
    assert.equal(primera.reloads.length, 3);
    assert.equal(primera.reloads[2].nss, '');
    assert.deepEqual(primera.reloads[2].inm_status_prospecto, []);
    assert.equal(primera.hidden.hidden_inm_status_prospecto.value, '');
    assert.equal(primera.buttons.limpiar.disabled, true);
    assert.equal(pagina(almacen).buttons.limpiar.disabled, true);
});

test('avisa cuando el navegador no permite guardar los filtros', () => {
    const almacen = storage();
    almacen.setItem = () => { throw new Error('almacenamiento bloqueado'); };
    const lista = pagina(almacen);
    lista.change('nss', '123', 'input');
    lista.click('filtrar');
    assert.equal(lista.alerts.length, 1);
    assert.equal(lista.reloads[0].nss, '123');
});

test('cada catalogo restaura sus propios filtros y exportacion sin afectar a los demas', () => {
    const almacen = storage();
    const catalogos = ['inm_prospecto', 'inm_ubicacion', 'inm_prospecto_ubicacion', 'inm_comprador'];
    catalogos.forEach((catalogo, indice) => {
        const lista = pagina(almacen, 1, catalogo);
        lista.change('id', String(indice + 1), 'input');
        lista.change('ubicacion', 'UBICACION ' + indice, 'input');
        lista.change('inm_status_' + catalogo.slice(4), ['ACTIVO']);
        lista.runTimers();
    });

    catalogos.forEach((catalogo, indice) => {
        const lista = pagina(almacen, 1, catalogo);
        const status = 'inm_status_' + catalogo.slice(4);
        assert.equal(lista.inicial.id, String(indice + 1));
        assert.equal(lista.inicial.ubicacion, 'UBICACION ' + indice);
        assert.deepEqual(Array.from(lista.inicial[status]), ['ACTIVO']);
        assert.equal(lista.hidden['hidden_' + status].value, 'ACTIVO');
        assert.equal(pagina(almacen, 2, catalogo).inicial.id, '');
    });

    pagina(almacen, 1, 'inm_ubicacion').click('limpiar');
    assert.equal(pagina(almacen, 1, 'inm_ubicacion').inicial.id, '');
    assert.equal(pagina(almacen, 1, 'inm_prospecto_ubicacion').inicial.id, '3');
});

test('el panel de ubicaciones de compradores respeta el estado restaurado y se cierra al limpiar', () => {
    const almacen = storage();
    const lista = pagina(almacen, 1, 'inm_comprador');
    lista.change('inm_status_comprador', ['DETENIDO']);
    lista.runTimers();
    assert.equal(lista.paneles['split-view'].classes.has('con-ubicaciones'), true);

    const vuelta = pagina(almacen, 1, 'inm_comprador');
    assert.equal(vuelta.paneles['split-view'].classes.has('con-ubicaciones'), true);
    vuelta.click('limpiar');
    vuelta.runTimers();
    assert.equal(vuelta.paneles['split-view'].classes.has('con-ubicaciones'), false);
    assert.equal(vuelta.reloads.length, 1);
});
