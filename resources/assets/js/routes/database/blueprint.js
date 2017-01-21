const AutoUnsignedColumns = [
    "unsignedBigInteger",
    "unsignedInteger",
    "unsignedMediumInteger",
    "unsignedSmallInteger",
    "unsignedTinyInteger",
];

export const IncrementColumns = [
    "bigIncrements",
    "increments",
    "mediumIncrements",
    "smallIncrements"
];


const IntegerTypeColumns = [
    "bigInteger",
    "integer",
    "mediumInteger",
    "smallInteger",
    "tinyInteger"
];

export const EnumTypeWarning = 'Modifying any column in a table that also has a column of type enum is not currently supported by Laravel.';

export function makeTableColumn(col) {

    var typeName = col.attributes.type;

    if(col.attributes.unsigned) {

        if(col.attributes.autoIncrement == true) {
            if(IntegerTypeColumns.indexOf(typeName) !== -1) {
                var newTypeName = _.replace(_.upperFirst(typeName), 'Integer', 'Increments');
                newTypeName = _.lowerFirst(newTypeName);

                if(IncrementColumns.indexOf(newTypeName) !== -1) {
                    col.attributes.type = newTypeName;
                }
            }


        } else if(IntegerTypeColumns.indexOf(typeName) !== -1) {
            var newType = 'unsigned' + _.upperFirst(typeName);
            if(AutoUnsignedColumns.indexOf(newType) !== -1) {
                col.attributes.type = newType;
            }
        }

    }

    // sets structure of column updates
    col.updates = {
        change_name : null,
        change_type: null,
        change_length: null,
        change_nullable: null,
        change_primaryKey: null,
        change_default: null,
        dropColumn: false,
    };

    return col;
}

