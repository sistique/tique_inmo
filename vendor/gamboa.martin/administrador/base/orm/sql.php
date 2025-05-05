<?php
namespace base\orm;
use config\database;
use gamboamartin\administrador\modelado\params_sql;
use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use stdClass;

class sql{
    private errores $error;
    public function __construct(){
        $this->error = new errores();
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Crea una sentencia SQL para agregar una nueva columna a una tabla.
     *
     * @param string $campo El nombre de la nueva columna a agregar.
     * @param string $table El nombre de la tabla a la que se agregará la nueva columna.
     * @param string $tipo_dato El tipo de dato de la nueva columna.
     * @param string $longitud Opcional. La longitud del nuevo campo, si aplicable. Por defecto es una cadena vacía.
     * @param bool $not_null Opcional. Si es true integra el NOT NULL si no lo deja libre.
     * @return string|array Devuelve la sentencia SQL para agregar la nueva columna a la tabla. O array si existe error
     */
    final public function add_column(string $campo, string $table, string $tipo_dato, string $default = '',
                                     string $longitud = '', bool $not_null = true): string|array
    {
        $campo = trim($campo);
        $table = trim($table);
        $tipo_dato = trim($tipo_dato);
        $tipo_dato = strtoupper($tipo_dato);

        $longitud = trim($longitud);
        if($tipo_dato === 'VARCHAR'){
            $longitud = '255';
        }

        $longitud_sql = '';
        if($longitud !== ''){
            $longitud_sql = "($longitud)";
        }

        $not_null_sql = '';
        if($not_null){
            $not_null_sql = 'NOT NULL';
        }

        $valida = $this->valida_column(campo:$campo,table:  $table, tipo_dato: $tipo_dato, longitud: $longitud);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos',data: $valida);
        }

        $default = $this->default(value: $default);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener default',data: $default);
        }

        return trim("ALTER TABLE $table ADD $campo $tipo_dato $longitud_sql $default $not_null_sql;");

    }

    /**
     * Genera una sentencia SQL para alterar una tabla con base en los parámetros proporcionados.
     *
     * @param string $campo El nombre del campo en la tabla que se va a modificar.
     * @param string $statement La declaración SQL a aplicar, puede ser 'ADD', 'DROP', 'RENAME' o 'MODIFY'.
     * @param string $table El nombre de la tabla a la que se va a aplicar la declaración.
     * @param string $longitud Opcional. La longitud del campo en caso de que se agregue o modifique un campo.
     * @param string $new_name Opcional. El nuevo nombre del campo en caso de que se esté renombrando.
     * @param string $tipo_dato Opcional. El tipo de dato del campo en caso de que se agregue o modifique un campo.
     * @return array|string Devuelve una cadena con la sentencia SQL generada.
     */
    final public function alter_table(
        string $campo, string $statement, string $table, string $longitud = '', string $new_name = '',
        string $tipo_dato = '', bool $valida_pep_8 = true): array|string
    {
        $sql = '';

        if($statement === 'ADD'){
            $sql = $this->add_column(campo: $campo,table:  $table,tipo_dato:  $tipo_dato,longitud: $longitud);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar sql',data: $sql);
            }

        }
        if($statement === 'DROP'){
            $sql = $this->drop_column(campo: $campo,table:  $table);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar sql',data: $sql);
            }
        }
        if($statement === 'RENAME'){
            $sql = $this->rename_column(campo: $campo, new_name: $new_name, table: $table);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar sql',data: $sql);
            }
        }
        if($statement === 'MODIFY'){
            $sql = $this->modify_column(
                campo: $campo, table: $table,tipo_dato: $tipo_dato,longitud: $longitud, valida_pep_8: $valida_pep_8);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar sql',data: $sql);
            }
        }

        return $sql;

    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Crea una tabla en la base de datos SQL.
     *
     * @param stdClass $campos Un objeto con los campos y características de cada campo en la tabla
     * @param string $table Nombre de la tabla a ser creado
     *
     * @throws errores Excepción cuando algún error ocurre durante el proceso de creación de la tabla.
     *
     * @return array|stdClass Regresa un objeto que contiene la sentencia SQL creada y la descripción de los campos
     * de la tabla. En caso de error, regresa un arreglo con información del error.
     *
     * @example
     * $object = new stdClass;
     * $object->nombre = 'varchar(255)';
     * $object->edad = 'int';
     * create_table($object, 'users');
     *
     * @version 16.37.0
     *
     */
    final public function create_table(stdClass $campos, string $table): array|stdClass
    {
        if(count((array)$campos) === 0){
            return $this->error->error(mensaje: 'Error campos esta vacio',data: $campos, es_final: true);
        }
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error al table esta vacia',data: $table, es_final: true);
        }

        $datos_tabla = (new _create())->datos_tabla(campos: $campos);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener datos_tabla',data: $datos_tabla);
        }

        $sql = (new _create())->table(datos_tabla: $datos_tabla,table:  $table);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener create',data: $sql);
        }

        $data = new stdClass();
        $data->sql = $sql;
        $data->datos_tabla = $datos_tabla;

        return $data;

    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * La función data_index se utiliza para configurar un índice en una o varias columnas de una base de datos SQL.
     *
     * @param string $columna El nombre de la columna para la cual se creará el índice.
     * @param string $columnas_index Cadena que contiene los nombres de las columnas para las que se creará el índice, separados por comas.
     * @param string $index_name El nombre del índice a crear.
     *
     * @return array|stdClass Retorna un objeto stdClass con dos propiedades. "index_name" que almacena el nombre del índice,
     *                    y "columnas_index" que es una cadena que contiene los nombres de las columnas del índice separados por comas.
     *                    Si ocurre algún error, retorna un objeto error.
     *
     * @throws errores Si el nombre de la columna está vacío, la función arrojará un error.
     *
     * @example
     * $dataIndex = data_index('columna1', 'columna2,columna3', 'miIndice');
     * echo $dataIndex->index_name; // Imprime: miIndice_columna1
     * echo $dataIndex->columnas_index; // Imprime: columna2,columna3,columna1
     * @version 15.16.0
     */
    private function data_index(string $columna, string $columnas_index, string $index_name): array|stdClass
    {
        $columna = trim($columna);
        if($columna === ''){
            return $this->error->error(mensaje: 'Error columna esta vacia', data: $columna, es_final: true);
        }
        $coma = '';
        $guion = '';
        if($columnas_index!==''){
            $coma = ',';
            $guion = '_';
        }

        $index_name.=$guion.$columna;
        $columnas_index.=$coma.$columna;

        $data = new stdClass();
        $data->index_name = $index_name;
        $data->columnas_index = $columnas_index;

        return $data;

    }

    /**
     * POR DOCUMENTAR WIKI FINAL REV
     * Esta función se utiliza para preparar los datos necesarios para la creación de un índice único en una tabla SQL.
     *
     * @param array $columnas Arreglo de columnas sobre las que se va a construir el índice único. No debe estar vacío.
     * @param string $table Nombre de la tabla en la que se va a crear el índice. No debe estar vacío.
     * @param string $index_name Nombre opcional del índice único. Si se proporciona, este nombre se utilizará para el índice en lugar del predeterminado.
     * @return stdClass|array Si la función se ejecuta con éxito, devuelve un objeto stdClass que contiene los detalles del índice que se va a crear. Si ocurre un error, devuelve un objeto de error.
     *
     * @throws errores Se lanza si $columnas está vacío o si $table está vacía o si una columna dentro de $columnas está vacía.
     *
     * Ejemplo de uso:
     *
     * ```php
     * $columnas = ['nombre', 'apellido'];
     * $tabla = 'usuarios';
     *
     *
     * $resultado = data_index_unique($columnas, $tabla);
     * if (errores::error) {
     *     print_r (resultado)
     * } else {
     *     echo 'Datos de índice preparados con éxito. Índice a ser creado: ' . $resultado->index_name;
     * }
     *
     * ```
     * @version 15.22.0
     */
    private function data_index_unique(array $columnas, string $table, string $index_name = ''): array|stdClass
    {
        if(count($columnas) === 0){
            return $this->error->error(mensaje: 'Error columnas esta vacio', data: $columnas, es_final: true);
        }
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacia', data: $table, es_final: true);
        }
        $data = new stdClass();
        $data->columnas_index = '';
        $data->index_name = $table.'_unique_';
        foreach ($columnas as $columna){
            $columna = trim($columna);
            if($columna === ''){
                return $this->error->error(mensaje: 'Error columna esta vacia', data: $columna, es_final: true);
            }
            $data = $this->data_index(columna: $columna,columnas_index:  $data->columnas_index,
                index_name:  $data->index_name);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error obtener datos de index', data: $data);
            }
        }
        if(trim($index_name) !== ''){
            $data->index_name = trim($index_name);
        }

        return $data;

    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Genera una declaración SQL para establecer un valor predeterminado en una columna.
     *
     * @param string $value El valor predeterminado a establecer.
     * @return string Devuelve la declaración SQL para establecer un valor predeterminado.
     * @version 13.25.0
     */
    private function default(string $value): string
    {
        $value = trim($value);
        $sql = '';
        if($value !== ''){
            $sql = "DEFAULT '$value'";
        }
        return trim($sql);
    }

    /**
     * REG
     * Genera una sentencia SQL para describir la estructura de una tabla.
     *
     * Este método valida el nombre de la tabla utilizando el método `tabla` de la clase `val_sql`.
     * Si la validación es exitosa, genera y retorna la sentencia SQL para describir la tabla.
     * En caso de error, utiliza `$this->error->error()` para retornar detalles del error.
     *
     * @param string $tabla Nombre de la tabla a describir.
     *
     * @return string|array Retorna:
     *   - Una cadena SQL con la sentencia `DESCRIBE $tabla` si la validación es exitosa.
     *   - Un arreglo de error si la validación falla.
     *
     * @throws array Si:
     *   - El nombre de la tabla es inválido o está vacío.
     *   - Ocurre un error en la validación realizada por `val_sql::tabla`.
     *
     * @example
     *  Ejemplo 1: Generar la sentencia `DESCRIBE` para una tabla válida
     *  ---------------------------------------------------------------
     *  $tabla = "usuarios";
     *  $resultado = $this->describe_table($tabla);
     *  // $resultado => "DESCRIBE usuarios"
     *
     * @example
     *  Ejemplo 2: Error al pasar una tabla vacía
     *  -----------------------------------------
     *  $tabla = "";
     *  $resultado = $this->describe_table($tabla);
     *  // $resultado =>
     *  // [
     *  //     'error' => 1,
     *  //     'mensaje' => 'Error al validar tabla',
     *  //     'data' => [
     *  //         'error' => 1,
     *  //         'mensaje' => 'Error tabla esta vacia',
     *  //         'data' => '',
     *  //         ...
     *  //     ],
     *  //     ...
     *  // ]
     */
    final public function describe_table(string $tabla): string|array
    {
        // Valida que el nombre de la tabla sea válido
        $valida = (new val_sql())->tabla(tabla: $tabla);
        if (errores::$error) {
            return $this->error->error(
                mensaje: 'Error al validar tabla',
                data: $valida
            );
        }

        // Retorna la sentencia SQL para describir la tabla
        return "DESCRIBE $tabla";
    }



    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Elimina una columna de una tabla específica en la base de datos.
     *
     * @param string $campo La columna que se va a eliminar.
     * @param string $table La tabla de la cual se va a eliminar la columna.
     * @return string|array Devuelve una sentencia SQL generada para ejecutar la acción.
     *
     * @throws errores Si la columna o la tabla estan vacias o si hay un problema al generar la consulta SQL.
     *
     * @version 14.12.0
     */
    final public function drop_column(string $campo, string $table): string|array
    {
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo esta vacio', data: $campo, es_final: true);
        }
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacia', data: $table, es_final: true);
        }
        return trim("ALTER TABLE $table DROP COLUMN $campo;");

    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Genera la sentencia sql para la eliminacion de un indice
     * @param string $name_index Nombre del indice a eliminar
     * @param string $table Tabla o entidad donde se encuentra el indice
     * @return string|array
     * @version 15.22.0
     */
    final public function drop_index(string $name_index, string $table): string|array
    {
        $name_index = trim($name_index);
        if($name_index === ''){
            return $this->error->error(mensaje: 'Error name_index esta vacio', data: $name_index);
        }
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacio', data: $table);
        }

        $sql = "DROP INDEX '$name_index' ON $table;";
        return trim($sql);
    }

    /**
     * Genera una sentencia SQL para eliminar una tabla.
     *
     * @param string $table El nombre de la tabla a eliminar.
     * @return string Retorna la sentencia SQL para eliminar la tabla.
     */
    final public function drop_table(string $table): string
    {
        return "DROP TABLE $table";

    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Genera una sentencia SQL que crea una clave foránea (FOREIGN KEY) para una tabla determinada.
     *
     * @param string $table El nombre de la tabla a la que se agregará la clave foránea.
     * @param string $relacion_table El nombre de la tabla con la cual se establecerá la relación.
     * @param string $name_indice_opt Opcional. El nombre personalizado del índice. Si no se proporciona, se genera
     * automáticamente a partir del nombre de las tablas relacionadas.
     *
     * @return string|array Devuelve una cadena con la sentencia SQL generada para crear la clave foránea.
     * En caso de error, devuelve un array con información sobre el error.
     * @throws errores En caso de error, se lanza una excepción con información detallada sobre el mismo.
     * @version
     */
    final public function foreign_key(string $table, string $relacion_table, string $name_indice_opt = ''): string|array
    {
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacia', data: $table);
        }
        $relacion_table = trim($relacion_table);
        if($relacion_table === ''){
            return $this->error->error(mensaje: 'Error relacion_table esta vacia', data: $relacion_table);
        }

        $fk = $relacion_table.'_id';

        $name_indice = $this->name_index_foranea(
            name_indice_opt: $name_indice_opt,relacion_table:  $relacion_table,table:  $table);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error obtener name_indice', data: $name_indice);
        }

        return "ALTER TABLE $table ADD CONSTRAINT $name_indice FOREIGN KEY ($fk) REFERENCES $relacion_table(id);";


    }

    /**
     *  POR DOCUMENTAR EN WIKI FINAL REV
     * La función get_foraneas busca todas las claves foráneas en una tabla específica.
     *
     * @param string $table Es el nombre de la tabla en la que se buscarán las claves foráneas.
     * @param string $column_name Aplica si queremos obtener una sola columna.
     *
     * @return string|array Devuelve un array de registros que contienen la información de las claves foráneas.
     * Si no hay claves foráneas en la tabla o un error ocurre, la función puede retornar un array vacío o un objeto Error.
     *
     * @example
     * $foraneas = get_foraneas('mi_tabla');
     * foreach ($foraneas as $clave_foranea) {
     *     echo "Nombre de la clave foránea es " . $clave_foranea->nombre;
     *     echo "Tabla referenciada es " . $clave_foranea->tabla_referenciada;
     * }
     *
     * @version 20.6.0
     */
    final public function get_foraneas(string $table, string $column_name = ''): string|array
    {
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table vacia',data:  $table, es_final: true);
        }
        $db_name = (new database())->db_name;

        $column_name = trim ($column_name);
        $column_name_sql = '';
        if($column_name !== ''){
            $column_name_sql = "AND cl.COLUMN_NAME  = '$column_name'";
        }

        $sql = /** @lang MYSQL */
            "SELECT 
                    fk.CONSTRAINT_SCHEMA AS nombre_database,
                    fk.CONSTRAINT_NAME AS nombre_indice,
                    fk.TABLE_NAME AS nombre_tabla,
                    cl.COLUMN_NAME AS columna_foranea,
                    cl.REFERENCED_TABLE_SCHEMA AS nombre_database_relacion,
                    cl.REFERENCED_TABLE_NAME AS nombre_tabla_relacion,
                    cl.REFERENCED_COLUMN_NAME AS nombre_columna_relacion
                FROM information_schema.TABLE_CONSTRAINTS AS fk 
                LEFT JOIN information_schema.KEY_COLUMN_USAGE AS cl ON
	                fk.CONSTRAINT_SCHEMA = cl.CONSTRAINT_SCHEMA AND
	                fk.TABLE_NAME = cl.TABLE_NAME AND
	                fk.CONSTRAINT_NAME = cl.CONSTRAINT_NAME
                
	            WHERE fk.CONSTRAINT_SCHEMA = '$db_name'
                    AND fk.TABLE_NAME = '$table'
                    AND fk.CONSTRAINT_TYPE = 'FOREIGN KEY'
                    $column_name_sql ;";

        return trim($sql);

    }




    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Crea un índice único en una tabla.
     *
     * @param array $columnas
     *   Las columnas de la tabla en las que se creará el índice.
     * @param string $table
     *   El nombre de la tabla a la que se agregará el índice único.
     * @param string $index_name
     *   El nombre del índice (opcional).
     *   Si no se proporciona, se generará un nombre de índice predeterminado.
     *
     * @return string|array
     *   Si no hay errores durante la generación y ejecución de SQL,
     *   devuelve el resultado de la ejecución SQL.
     *   Si ocurre un error durante la generación de SQL,
     *   devuelve un error con los detalles de $sql.
     *   Si ocurre un error durante la ejecución de SQL,
     *   devuelve un error con los detalles de $exe.
     *
     * @throws errores
     *   Si ocurre un error durante la generación o ejecución de SQL.
     *
     * @example
     *   $index = $object->index_unique(["columna1", "columna2"], "mi_tabla", "mi_indice_unico");
     *   if ($index !== true) {
     *        echo "Error al agregar el índice único: " . $index;
     *   } else {
     *        echo "Índice único agregado con éxito";
     *   }
     * @version 16.39.0
     */
    final public function index_unique(array $columnas, string $table, string $index_name = ''): string|array
    {
        if(count($columnas ) === 0){
            return $this->error->error(mensaje: 'Error columnas esta vacia', data: $columnas, es_final: true);
        }
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacia', data: $table, es_final: true);
        }

        $data = $this->data_index_unique(columnas: $columnas,table:  $table, index_name: $index_name);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error obtener datos de index', data: $data);
        }

        $sql = "CREATE UNIQUE INDEX $data->index_name  ON $table ($data->columnas_index);";
        return trim($sql);

    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Inicializa parámetros base para la consulta SQL.
     *
     * Este método permite inicializar los parámetros (clausura $params_base) para la consulta SQL, dada una clave.
     * En caso de que la clave esté vacía, se devuelve un error. También realiza una validación, si la clave no existe
     * en $params_base, intenta inicializar ese parámetro. Si ocurre un error durante la inicialización, también devuelve un error.
     *
     * @param string $key La clave que se usará para inicializar el parámetro.
     * @param stdClass $params_base La clausura que contiene los parámetros que serán utilizados en la consulta SQL.
     *
     * @return array|stdClass Retorna el conjunto de parámetros inicializados. En caso de error, devuelve el detalle del error.
     * @version 16.229.0
     */
    private function inicializa_param(string $key, stdClass $params_base): array|stdClass
    {
        $key = trim($key);
        if($key === ''){
            return $this->error->error(mensaje: 'Error key esta vacio', data: $key);
        }

        if(!isset($params_base->$key)){
            $params_base = $this->init_param(key: $key,params_base:  $params_base);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al inicializar param', data: $params_base);
            }
        }
        return $params_base;
    }


    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Inicializa el autoincremento de una tabla en una base de datos MySQL.
     *
     * Este método permite inicializar el autoincremento de una tabla específica en una base de datos MySQL.
     * Se verifica que la tabla no esté vacía y se devuelve un error en caso contrario.
     * La sentencia SQL ALTER TABLE se utiliza para inicializar el autoincremento de la tabla a 0.
     *
     * @param string $table El nombre de la tabla para la cual se inicializará el autoincremento.
     *
     * @return string|array Retorna la sentencia SQL utilizada para inicializar el autoincremento de la tabla a 0.
     * En caso de error, devuelve el detalle del error.
     * @version 17.24.0
     */
    final public function init_auto_increment(string $table): string|array
    {
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacia', data: $table, es_final: true);
        }

        return /** @lang MYSQL */ "ALTER TABLE $table AUTO_INCREMENT=0;";
    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Inicializa un nuevo parámetro en el objeto de parámetros pasado.
     *
     * @param string $key - La clave del parámetro que se va a inicializar.
     * @param stdClass $params_base - El objeto en el que se inicializará el nuevo parámetro.
     *
     * @return stdClass|array - Retorna el objeto de parámetros después de inicializar el nuevo parámetro.
     * Si la clave está vacía, en su lugar se retorna un array con mensaje de error.
     * @version 16.217.0
     */
    private function init_param(string $key, stdClass $params_base): stdClass|array
    {
        $key = trim($key);
        if($key === ''){
            return $this->error->error(mensaje: 'Error key esta vacio', data: $key);
        }
        $params_base->$key = '';
        return $params_base;
    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Inicializa los parámetros de la consulta SQL.
     *
     * Esta función prepara los parámetros para una consulta SQL antes de que se ejecute. Este proceso puede implicar
     * la limpieza de los datos, la comprobación de tipos de datos o la asignación de valores por defecto.
     *
     * @param stdClass $params_base
     * @return array|stdClass Los parámetros inicializados que están listos para utilizar en una consulta.
     * @version 16.234.0
     */
    private function init_params(stdClass $params_base): array|stdClass
    {
        $params_base_ = $params_base;

        $keys_params[] = 'seguridad';
        $keys_params[] = 'group_by';
        $keys_params[] = 'order';
        $keys_params[] = 'limit';
        $keys_params[] = 'offset';

        foreach ($keys_params as $key){
            $params_base_ = $this->inicializa_param(key: $key, params_base: $params_base_);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al inicializar param', data: $params_base_);
            }
        }

        return $params_base_;
    }

    /**
     * REG
     * Limpia todos los espacios dobles de una cadena.
     *
     * Esta función toma una cadena de texto y, mediante un bucle de iteraciones, reemplaza todas las ocurrencias de dos
     * espacios consecutivos ("  ") por un único espacio (" "). Se realizan hasta *n_iteraciones* repeticiones para asegurar
     * que se eliminen todos los espacios dobles, especialmente en casos en los que existan más de dos espacios consecutivos.
     *
     * **Detalles de la implementación:**
     * - Se utiliza un bucle `while` que se ejecuta hasta que se alcance el número máximo de iteraciones especificado.
     * - En cada iteración, se reemplazan todas las ocurrencias de dos espacios por un espacio simple utilizando `str_replace()`.
     *
     * @param string $txt           La cadena de texto que se desea limpiar de espacios dobles.
     * @param int    $n_iteraciones El número máximo de iteraciones que se ejecutarán para limpiar la cadena.
     *                               Por defecto es 10.
     *
     * @return string               La cadena resultante después de eliminar los espacios dobles.
     *
     * @example
     * // Ejemplo 1: Limpiar una cadena con espacios dobles
     * $textoOriginal = "Este   es    un   ejemplo     de   cadena  con espacios   dobles.";
     * $textoLimpio = $objeto->limpia_espacios_dobles($textoOriginal);
     * // Resultado esperado: "Este es un ejemplo de cadena con espacios dobles."
     *
     * @example
     * // Ejemplo 2: Limitar el número de iteraciones
     * $textoOriginal = "Múltiples        espacios";
     * // Con solo 2 iteraciones, es posible que aún queden espacios dobles si existen más de dos espacios consecutivos.
     * $textoLimpio = $objeto->limpia_espacios_dobles($textoOriginal, 2);
     *
     * @see str_replace() Para más información sobre la función utilizada para reemplazar las cadenas.
     */
    final public function limpia_espacios_dobles(string $txt, int $n_iteraciones = 10): string
    {
        $iteracion = 0;
        while ($iteracion <= $n_iteraciones) {
            $txt = str_replace('  ', ' ', $txt);
            $iteracion++;
        }
        return $txt;
    }


    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Calcula la longitud de un dato de tipo string|int|float dependiendo del tipo de dato que se le pase.
     *
     * @param string|int|float $longitud Longitud inicial del dato.
     * @param string $tipo_dato Tipo del dato. Puede ser 'BIGINT', 'VARCHAR', 'DOUBLE', 'TIMESTAMP', 'TEXT'.
     *
     * @return int|string Devuelve la longitud calculada. Si el tipo de dato es 'TIMESTAMP' o 'TEXT' devuelve una cadena vacía.
     *
     * @example
     *
     * longitud('', 'BIGINT'); // Devolverá 100
     * longitud('', 'VARCHAR'); // Devolverá 255
     * longitud('', 'DOUBLE'); // Devolverá '100,4'
     * longitud('', 'TIMESTAMP'); // Devolverá ''
     * longitud('', 'TEXT'); // Devolverá ''
     * @version 16.49.0
     */
    private function longitud(string|int|float $longitud, string $tipo_dato): int|string
    {
        $tipo_dato = strtoupper($tipo_dato);
        if($tipo_dato === 'BIGINT'){
            if($longitud === ''){
                $longitud = 100;
            }
        }
        if($tipo_dato === 'VARCHAR'){
            if($longitud === ''){
                $longitud = 255;
            }
        }
        if($tipo_dato === 'DOUBLE'){
            if($longitud === ''){
                $longitud = '100,4';
            }
        }

        if($tipo_dato === 'TIMESTAMP'){
            $longitud = '';
        }
        if($tipo_dato === 'TEXT'){
            $longitud = '';
        }
        if($tipo_dato === 'DATE'){
            $longitud = '';
        }
        if($tipo_dato === 'DATETIME'){
            $longitud = '';
        }
        if($tipo_dato === 'LONGBLOB'){
            $longitud = '';
        }
        if($tipo_dato === 'BLOB'){
            $longitud = '';
        }

        return $longitud;

    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Genera el segmento de longitud para una declaración SQL a partir de un valor de longitud y tipo de dato.
     *
     * @param int|string|float $longitud Un valor que especifica la longitud que se desea configurar.
     * @param string $tipo_dato Descripción del tipo de dato que se desea manejar.
     *
     * @return array|string Retorna la cadena SQL de longitud generada si no hay ningun error.
     *                      En caso de error retorna un arreglo con la información del error
     *                      generada por la función error() desde la clase errores.
     *
     * @version 16.51.0
     */
    private function longitud_sql(int|string|float $longitud, string $tipo_dato): array|string
    {
        $longitud = $this->longitud(longitud: $longitud,tipo_dato: $tipo_dato);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar longitud', data: $longitud);
        }

        $longitud_sql = $this->longitud_sql_ini(longitud: $longitud);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar longitud_sql', data: $longitud_sql);
        }
        return $longitud_sql;

    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Devuelve una cadena de texto que representa la longitud en SQL.
     *
     * @param string|float|int $longitud La longitud del elemento SQL.
     *
     * @return string Una cadena de texto que representa la longitud en SQL.
     *                En caso de que $longitud sea una cadena vacía, se devuelve una cadena vacía.
     * @version 16.50.0
     */
    private function longitud_sql_ini(string|float|int $longitud): string
    {
        $longitud_sql = '';
        if($longitud !== ''){
            $longitud_sql = "($longitud)";
        }
        return $longitud_sql;

    }

    /**
     * POR MODIFICAR EN WIKI FINAL REV
     * Modifica una columna en una tabla de la base de datos.
     *
     * @param string $campo     El nombre del campo de la tabla a modificar.
     * @param string $table     El nombre de la tabla en la cual se encuentra el campo a modificar.
     * @param string $tipo_dato El nuevo tipo de dato que se asignará al campo.
     * @param string $longitud  La longitud máxima permitida para los valores del campo en el caso que el tipo de dato lo requiera.
     * @param bool   $valida_pep_8 Indica si se deben aplicar las reglas del manual de estilo de código PEP 8 a los datos de entrada. Por defecto es true.
     *
     * @return string|array retorna una sentencia SQL de modificación de columna si no encuentran errores,
     * de lo contrario retorna un array con la descripción del error.
     *
     * @throws errores si hay un error al validar los datos o al inicializar la longitud SQL.
     * @version 16.92.0
     */
    final public function modify_column(
        string $campo, string $table, string $tipo_dato, string $longitud = '', bool $valida_pep_8 = true): string|array
    {
        $campo = trim($campo);
        $table = trim($table);
        $tipo_dato = trim($tipo_dato);
        $longitud = trim($longitud);

        $valida = $this->valida_datos_modify(
            campo: $campo,table:  $table,tipo_dato:  $tipo_dato, valida_pep_8:  $valida_pep_8);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        $longitud_sql = $this->longitud_sql(longitud: $longitud,tipo_dato:  $tipo_dato);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar longitud_sql', data: $longitud_sql);
        }


        return "ALTER TABLE $table MODIFY COLUMN $campo $tipo_dato $longitud_sql;";

    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Este método genera un nombre para un índice foráneo.
     *
     * @param string $name_indice_opt Nombre opcional para el índice foráneo.
     * @param string $relacion_table Nombre de la tabla de relación.
     * @param string $table Nombre de la tabla que contiene el índice foráneo.
     * @return string|array Retorna el nombre generado para el índice foráneo.
     *                      Si ocurre un error, retorna un array con la descripción del error.
     * @version 16.132.0
     */
    final public function name_index_foranea(
        string $name_indice_opt, string $relacion_table, string $table): string|array
    {
        $relacion_table = trim($relacion_table);
        if($relacion_table === ''){
            return $this->error->error(mensaje: 'Error relacion_table esta vacia', data: $relacion_table);
        }
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacia', data: $table);
        }

        $fk = $relacion_table.'_id';
        $name_indice = $table.'_'.$fk;
        $name_indice_opt = trim($name_indice_opt);
        if($name_indice_opt !==''){
            $name_indice = $name_indice_opt;
        }
        return $name_indice;

    }
    final public function rename_column(string $campo, string $new_name, string $table): string|array
    {
        $campo = trim($campo);
        $new_name = trim($new_name);
        $table = trim($table);

        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo esta vacio', data: $campo, es_final: true);
        }
        if($new_name === ''){
            return $this->error->error(mensaje: 'Error new_name esta vacio', data: $new_name, es_final: true);
        }
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacio', data: $table, es_final: true);
        }
        if(is_numeric($campo)){
            return $this->error->error(mensaje: 'Error campo es numerico', data: $campo, es_final: true);
        }
        if(is_numeric($new_name)){
            return $this->error->error(mensaje: 'Error new_name es numerico', data: $new_name, es_final: true);
        }
        if(is_numeric($table)){
            return $this->error->error(mensaje: 'Error table es numerico', data: $table, es_final: true);
        }

        return "ALTER TABLE $table RENAME COLUMN $campo to $new_name;";
    }

    /**
     * REG
     * Genera una consulta SQL para mostrar las tablas de la base de datos.
     *
     * Este método:
     * 1. Permite filtrar las tablas por un patrón de búsqueda opcional.
     * 2. Genera una consulta `SHOW TABLES` con o sin filtro basado en el parámetro `$entidad`.
     *
     * @param string $entidad (Opcional) Un patrón para filtrar las tablas. Si se proporciona,
     *                        se genera una consulta con la cláusula `LIKE`.
     *
     * @return string
     *   - Retorna una cadena con la consulta SQL generada.
     *   - La consulta puede incluir un filtro `LIKE` si `$entidad` no está vacío.
     *
     * @example
     *  Ejemplo 1: Mostrar todas las tablas
     *  -----------------------------------
     *  $sql = $this->show_tables();
     *  // Resultado:
     *  // "SHOW TABLES"
     *
     * @example
     *  Ejemplo 2: Filtrar tablas por un patrón
     *  ---------------------------------------
     *  $entidad = 'usuarios%';
     *  $sql = $this->show_tables($entidad);
     *  // Resultado:
     *  // "SHOW TABLES LIKE 'usuarios%'"
     *
     * @example
     *  Ejemplo 3: Filtrar tablas con nombre específico
     *  -----------------------------------------------
     *  $entidad = 'clientes';
     *  $sql = $this->show_tables($entidad);
     *  // Resultado:
     *  // "SHOW TABLES LIKE 'clientes'"
     */
    final public function show_tables(string $entidad = ''): string
    {
        // Limpia el parámetro para evitar inyecciones SQL
        $entidad = trim($entidad);

        // Inicializa la cláusula WHERE como vacía
        $where = '';

        // Si se proporciona una entidad, agrega un filtro LIKE
        if ($entidad !== '') {
            $where = "LIKE '$entidad'";
        }

        // Construye y retorna la consulta SQL
        $sql = "SHOW TABLES $where";
        return trim($sql);
    }


    /**
     * POR DOCUMENTAR EN WIKI
     * Esta función genera la consulta SQL a partir de la consulta base y parámetros dados.
     *
     * @param string $consulta_base La consulta SQL base que se usará para generar la consulta final.
     * @param stdClass $params_base Los parámetros que serán añadidos a la consulta final.
     * @param string $sql_extra Cualquier SQL extra o ajustes que se añadirán a la consulta final.
     *
     * @return string|array Devuelve la consulta final generada como un string, o un array en caso de error.
     *
     * @final
     * @public
     * @version 16.235.0
     *
     */
    final public function sql_select(string $consulta_base, stdClass $params_base, string $sql_extra): string|array
    {
        $consulta_base = trim($consulta_base);
        if($consulta_base === ''){
            return $this->error->error(mensaje: 'Error la consulta no puede venir vacia', data: $consulta_base);
        }

        $params_base_ = $this->init_params(params_base: $params_base);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar param', data: $params_base_);
        }

        $consulta = $consulta_base.' '.$sql_extra.' '.$params_base_->seguridad.' ';
        $consulta.= $params_base_->group_by.' '.$params_base_->order.' '.$params_base_->limit.' '.$params_base_->offset;
        return $consulta;
    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Inicializa una consulta SELECT SQL.
     *
     * @param bool $aplica_seguridad Indica si se deben aplicar medidas de seguridad a la consulta.
     * @param array $columnas Array con los nombres de las columnas que se incluirán en la consulta.
     * @param bool $columnas_en_bruto Indica si los nombres de las columnas se deben pasar sin procesar.
     * @param bool $con_sq Indica si se debe incluir una cláusula SQ en la consulta.
     * @param array $extension_estructura Array con información adicional sobre la estructura de la consulta.
     * @param array $group_by Array con los nombres de las columnas para agrupar los resultados de la consulta.
     * @param int $limit Número máximo de resultados a devolver en la consulta.
     * @param modelo $modelo Instancia del modelo que realiza la consulta.
     * @param int $offset Número de resultados a ignorar al inicio del conjunto de resultados.
     * @param array $order Array con los nombres de las columnas para ordenar los resultados de la consulta.
     * @param array $renombres Array con los nombres de las columnas a cambiar en la consulta.
     * @param string $sql_where_previo Cadena con condiciones WHERE previas a incorporar en la consulta.
     *
     * @return array|stdClass Devuelve un array o un objeto stdClass.
     *                         El objeto o array contiene los parámetros finales de la consulta y la consulta en sí misma.
     *
     * @throws errores Puede lanzar una excepción si se produce un error al generar los parámetros de la consulta o la consulta en sí.
     * @version 16.214.0
     */
    final public function sql_select_init(bool $aplica_seguridad, array $columnas, bool $columnas_en_bruto,
                                          bool $con_sq, array $extension_estructura, array $group_by, int $limit,
                                          modelo $modelo, int $offset, array $order, array $renombres,
                                          string $sql_where_previo): array|stdClass
    {
        if($limit<0){
            return $this->error->error(mensaje: 'Error limit debe ser mayor o igual a 0 en '.$modelo->tabla,
                data:  $limit);
        }
        if($offset<0){
            return $this->error->error(mensaje: 'Error $offset debe ser mayor o igual a 0 en '.$modelo->tabla,
                data: $offset);

        }

        $params_base = (new params_sql())->params_sql(aplica_seguridad: $aplica_seguridad,group_by: $group_by,
            limit:  $limit,modelo_columnas_extra: $modelo->columnas_extra, offset: $offset, order: $order,
            sql_where_previo: $sql_where_previo);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener parametros base en '.$modelo->tabla,
                data: $params_base);
        }

        $consulta_base = $modelo->genera_consulta_base(columnas: $columnas, columnas_en_bruto: $columnas_en_bruto,
            con_sq: $con_sq, extension_estructura: $extension_estructura, renombradas: $renombres);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar consulta en '.$modelo->tabla, data: $consulta_base);
        }

        $data = new stdClass();
        $data->params = $params_base;
        $data->consulta_base = $consulta_base;
        return $data;
    }

    /**
     * Funcion que genera un UPDATE de tipo SQL
     * @param string $campos_sql Campos en forma sql para update
     * @param int $id Identificador
     * @param string $tabla Tabla en ejecucion
     * @return string|array
     * @version 1.81.17
     */
    public function update(string $campos_sql, int $id, string $tabla): string|array
    {
        $valida = (new val_sql())->tabla(tabla: $tabla);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar tabla', data: $valida);
        }
        $campos_sql = trim($campos_sql);
        if($campos_sql === ''){
            return $this->error->error(mensaje: 'Error $campos_sql estan vacios', data: $campos_sql);
        }
        if($id<=0){
            return $this->error->error(mensaje: 'Error $id debe ser mayor a 0', data: $id);
        }


        return 'UPDATE ' . $tabla . ' SET ' . $campos_sql . "  WHERE id = $id";
    }

    /**
     * TOTAL
     * Valida los valores de los argumentos campo, tabla y tipo de dato. Si alguno de ellos es una cadena vacía, devuelve un error.
     *
     * @param string $campo El nombre de la columna a validar.
     * @param string $table El nombre de la tabla a validar.
     * @param string $tipo_dato El tipo de dato a validar.
     * @return true|array Retorna verdadero si los argumentos son válidos, o un array con un mensaje de error si no lo son.
     * @url https://github.com/gamboamartin/administrador/wiki/administrador.base.orm.sql.valida_column
     */
    final function valida_column(string $campo, string $table, string $tipo_dato, string $longitud = ''): true|array
    {

        $valida = $this->valida_column_base(campo: $campo,table:  $table);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos de entrada',data: $valida);
        }

        $tipo_dato = trim($tipo_dato);
        if($tipo_dato === ''){
            return $this->error->error(mensaje: 'Error tipo_dato esta vacio',data: $tipo_dato, es_final: true);
        }
        if(is_numeric($tipo_dato)){
            return $this->error->error(mensaje: 'Error tipo_dato debe ser un texto',data: $tipo_dato, es_final: true);
        }

        $longitud = trim($longitud);

        $tipo_dato = strtoupper($tipo_dato);
        if($tipo_dato === 'VARCHAR'){
            if($longitud === ''){
                return $this->error->error(
                    mensaje: 'Error tipo_dato esta VARCHAR entonces longitud debe ser u numero entero',
                    data: $tipo_dato, es_final: true);
            }

        }

        return true;

    }

    /**
     * TOTAL
     * Valida los nombres de una columna y una tabla para asegurar que sean cadenas de texto no vacías.
     *
     * @param string $campo Nombre de la columna de la base de datos que se desea validar.
     *                      Este valor debe ser una cadena no vacía y no debe ser numérico.
     * @param string $table Nombre de la tabla de la base de datos que se desea validar.
     *                      Este valor debe ser una cadena no vacía y no debe ser numérico.
     *
     * @return true|array Retorna true si ambos parámetros son válidos. Si algún parámetro no es válido,
     *                    retorna un array que contiene un mensaje de error y el dato problemático.
     * @url https://github.com/gamboamartin/administrador/wiki/administrador.base.orm.sql.valida_column_base
     */
    final public function valida_column_base(string $campo, string $table): true|array
    {
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo esta vacio',data: $campo, es_final: true);
        }
        if(is_numeric($campo)){
            return $this->error->error(mensaje: 'Error campo debe ser un texto',data: $campo, es_final: true);
        }

        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacia',data: $table, es_final: true);
        }
        if(is_numeric($table)){
            return $this->error->error(mensaje: 'Error table debe ser un texto',data: $table, es_final: true);
        }
        return true;

    }


    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Método para validar los datos antes de la modificación.
     *
     * Esta función toma como argumentos los datos proporcionados y realiza varias comprobaciones para
     * asegurarse de que son válidos para la modificación. Si los datos no son válidos, se devuelve un error.
     *
     * @param string $campo Nombre del campo en la tabla. Este parámetro es obligatorio y no puede estar vacío.
     * @param string $table Nombre de la tabla en la base de datos. Este parámetro es obligatorio y no puede estar vacío.
     * @param string $tipo_dato Tipo de dato del campo que esta siendo validado. Este parametro es obligatorio y no puede estar vacío.
     * @param bool $valida_pep_8 Realiza validación de los datos contra las reglas de nombres de PEP 8. Este parámetro es opcional y por defecto es true.
     *
     * @return true|array Si los datos son válidos, devuelve true. Si no son válidos, devuelve un array con información del error.
     * @throws errores En caso de un fallo en la ejecución.
     * @version 16.89.0
     */
    final public function valida_datos_modify(
        string $campo, string $table, string $tipo_dato, bool $valida_pep_8 = true): true|array
    {
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo esta vacio', data: $campo, es_final: true);
        }
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacio', data: $table, es_final: true);
        }
        $tipo_dato = trim($tipo_dato);
        if($tipo_dato === ''){
            return $this->error->error(mensaje: 'Error tipo_dato esta vacio', data: $tipo_dato, es_final: true);
        }
        if($valida_pep_8) {
            $valida = $this->valida_pep_8(campo: $campo,table:  $table);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
            }
        }

        return true;

    }

    /**
     * REG
     * Valida la coherencia entre la llave y la cadena de valores SQL para una cláusula IN.
     *
     * Esta función se encarga de verificar que, si se proporciona un valor para la llave (es decir, el nombre
     * de la columna para la cláusula IN), también se proporcione una cadena de valores SQL, y viceversa.
     * Es decir:
     *
     * - Si **$llave** no es una cadena vacía, se requiere que **$values_sql** tampoco lo sea.
     * - Si **$values_sql** no es una cadena vacía, se requiere que **$llave** tampoco lo sea.
     *
     * En caso de que alguna de estas condiciones no se cumpla, se devuelve un array de error generado
     * mediante el método `error()` de la clase de manejo de errores. Si ambas entradas son coherentes,
     * la función retorna `true`, indicando que la validación fue exitosa.
     *
     * ## Ejemplos de Uso:
     *
     * ### Ejemplo 1: Validación exitosa
     * Se proporcionan ambos parámetros con información:
     *
     * ```php
     * $llave = "categoria_id";
     * $values_sql = "'10','20','30'";
     *
     * $resultado = $sqlObj->valida_in($llave, $values_sql);
     * // Resultado esperado: true
     * ```
     *
     * ### Ejemplo 2: Error por $values_sql vacío cuando $llave tiene valor
     * Se proporciona un valor para la llave, pero la cadena de valores está vacía:
     *
     * ```php
     * $llave = "categoria_id";
     * $values_sql = "";
     *
     * $resultado = $sqlObj->valida_in($llave, $values_sql);
     * // Resultado esperado: Array de error
     * // [
     * //     'error'         => 1,
     * //     'mensaje'       => 'Error si llave tiene info values debe tener info',
     * //     'mensaje_limpio'=> 'Error si llave tiene info values debe tener info',
     * //     'data'          => 'categoria_id',
     * //     ... (otros datos del error)
     * // ]
     * ```
     *
     * ### Ejemplo 3: Error por $llave vacío cuando $values_sql tiene valor
     * Se proporciona una cadena de valores, pero la llave está vacía:
     *
     * ```php
     * $llave = "";
     * $values_sql = "'10','20','30'";
     *
     * $resultado = $sqlObj->valida_in($llave, $values_sql);
     * // Resultado esperado: Array de error
     * // [
     * //     'error'         => 1,
     * //     'mensaje'       => 'Error si values_sql tiene info llave debe tener info',
     * //     'mensaje_limpio'=> 'Error si values_sql tiene info llave debe tener info',
     * //     'data'          => "'10','20','30'",
     * //     ... (otros datos del error)
     * // ]
     * ```
     *
     * ## Parámetros:
     *
     * @param string $llave       La llave o nombre de columna para la cláusula IN. Se espera que sea una cadena
     *                            no vacía si se desea especificar valores para la cláusula.
     * @param string $values_sql  La cadena SQL que contiene los valores a utilizar en la cláusula IN.
     *                            Generalmente, estos valores deben estar formateados y separados por comas,
     *                            por ejemplo: "'10','20','30'". Se espera que sea no vacía si se proporciona una llave.
     *
     * ## Valor de Retorno:
     *
     * @return bool|array         Devuelve `true` si la validación es exitosa; de lo contrario, devuelve un array
     *                            con los detalles del error generado por el método de manejo de errores.
     *
     * @see errores::error() Para el formato del array de error devuelto.
     */
    final public function valida_in(string $llave, string $values_sql): bool|array
    {
        $llave = trim($llave);
        $values_sql = trim($values_sql);
        if($llave !== ''){
            if($values_sql ===''){
                return $this->error->error(
                    mensaje: 'Error si llave tiene info values debe tener info',
                    data: $llave,
                    es_final: true
                );
            }
        }

        if($values_sql !== ''){
            if($llave ===''){
                return $this->error->error(
                    mensaje: 'Error si values_sql tiene info llave debe tener info',
                    data: $values_sql,
                    es_final: true
                );
            }
        }
        return true;
    }


    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * La función 'valida_pep_8' realiza la validación de texto para cumplir con PEP 8 (Estándar de estilo de código para el lenguaje de programación Python).
     * Sin embargo, está siendo utilizado aquí en el contexto de PHP, probablemente para asegurar un estilo de escritura consistente y limpio.
     *
     * @param string $campo Representa un campo específico que se quiere validar.
     * @param string $table Representa el nombre de la tabla donde se encuentra el campo.
     *
     * @return true|array Retorna verdadero si ambos, el campo y el nombre de la tabla, pasan la validación PEP 8.
     * En caso de que alguno de los dos no pase la validación, muestra un error.
     * @version 19.15.0
     */
    private function valida_pep_8(string $campo, string $table): true|array
    {
        $campo = trim($campo);
        $table = trim($table);
        $valida = (new validacion())->valida_texto_pep_8(txt: $campo);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar campo', data: $valida);
        }
        $valida = (new validacion())->valida_texto_pep_8(txt: $table);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar table', data: $valida);
        }
        return true;

    }

    /**
     * POR DOCUMENTAR WIKI
     * Esta función se utiliza para obtener información sobre los índices de una tabla SQL.
     *
     * @param string $table Nombre de la tabla de la cual se desea obtener información de los índices. No debe estar vacío.
     * @return string|array Si la función se ejecuta con éxito, devuelve una cadena de texto que representa la
     * consulta SQL para obtener los índices de la tabla especificada. Si ocurre
     * un error, devuelve un objeto de error.
     *
     * @throws errores Se lanza si $table está vacía.
     *
     * Ejemplo de uso:
     *
     * ```php
     * $tabla = 'usuarios';
     *
     *
     * $resultado = ver_indices($tabla);
     * if (errores::error) {
     *     print_r (resultado)
     * } else {
     *     echo 'Consulta SQL para obtener índices: ' . $resultado;
     * }
     *
     * ```
     * @version 15.22.0
     */
    final public function ver_indices(string $table): string|array
    {
        $table = trim($table);
        if($table === ''){
            return $this->error->error(mensaje: 'Error table esta vacia', data: $table);
        }
        $sql = "SHOW INDEXES FROM $table;";
        return trim($sql);

    }

}
