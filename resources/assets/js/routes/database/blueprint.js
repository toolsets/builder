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

export function makeTableColumn(col) {

    var typeName = col.attributes.type;
console.log('makeTableColumn');
    if(col.attributes.unsigned) {
        if(IntegerTypeColumns.indexOf(typeName) !== -1) {
            var newType = 'unsigned' + _.upperFirst(typeName);
            if(AutoUnsignedColumns.indexOf(newType) !== -1) {
                col.attributes.type = newType;
            }
        }
    }

    return col;
}

