import Base from "./Base";
import { default as AdminDatatable } from '../Yeb/Admin/Datatable';

export default class Datatable extends Base  {

    constructor(request, orderIndex = 1) {
        super(request);

        var datatable = new AdminDatatable();
        datatable.setup({
            order: [[ orderIndex, 'desc' ]]
        });
    }
    
}
