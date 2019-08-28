<template>
    <section class="content p-l-r-15" id="invoice-stmt">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">
                    <i class="fa fa-fw ti-credit-card"></i> Invoice
                </h3>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 col-sm-12 col-12 col-lg-6 col-xl-6 invoice_bg">
                        <h4><img src="../../img/color-logo.png" alt="clear"/></h4>
                        <h4><strong>Billing Details:</strong></h4>
                        <address>
                            Lewis Doe
                            <br/> 6889 Lunette Street
                            <br/> Melbourne,Austria
                            <br/> <strong>Phone:</strong>12-345-678
                            <br/> <strong>Mail Id:</strong> Adelle_Champlin@yahoo.com
                        </address>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12 col-lg-6 col-xl-6 invoice_bg text-right">
                        <div class="float-right">
                            <h4><strong>#678956 / 25 Sep 2016</strong></h4>
                            <h4><strong>Invoice Info:</strong></h4>
                            <address>
                                Tom Percy
                                <br/> 3946 Penn Street
                                <br/> Ohio,USA
                                <br/> <strong>Phone:</strong> 32-666-756
                                <br/> <strong>Mail Id:</strong> Lucy_Maggio16@yahoo.com
                            </address>
                            <span></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed" id="customtable">
                            <thead>
                            <tr class="bg-primary">
                                <th>
                                    <strong>Item Name</strong>
                                </th>
                                <th>
                                    <strong>Unit Cost</strong>
                                </th>
                                <th class="text-center">
                                    <strong>
                                        Quantity
                                    </strong>
                                </th>
                                <th></th>
                                <th class="text-right">
                                    <strong>Total</strong>
                                </th>
                                <th class="text-center" id="add_row"><i class="fa fa-fw ti-plus"  @click="addRow"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(input, index) in tr">
                                <td class="emptyrow" contenteditable >{{ input.itemname }}</td>
                                <td class="emptyrow" contenteditable>{{input.cost}}</td>
                                <td class="emptyrow text-center" contenteditable>{{input.qunatity}}</td>
                                <td class="emptyrow text-right" contenteditable>
                                </td>
                                <td class="emptyrow text-right" contenteditable>{{input.total}}
                                </td>
                                <td class="text-center row_delete"><i class="fa fa-fw ti-close" @click="removeRow(index)"></i></td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td class="highrow"></td>
                                <td class="highrow"></td>
                                <td class="highrow text-center"></td>
                                <td class="highrow text-right">
                                    <strong>
                                        Sub Total: &nbsp;
                                    </strong>
                                </td>
                                <td class="highrow text-right">
                                    <strong contenteditable>$1838</strong>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="emptyrow"></td>
                                <td class="emptyrow"></td>
                                <td class="emptyrow text-center"></td>
                                <td class="emptyrow text-right">
                                    <strong>
                                        Vat: &nbsp;
                                    </strong>
                                </td>
                                <td class="highrow text-right">
                                    <strong contenteditable>$20</strong>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="emptyrow">
                                    <i class="livicon" data-name="barcode" data-size="60" data-loop="true"></i>
                                </td>
                                <td class="emptyrow"></td>
                                <td class="emptyrow text-center"></td>
                                <td class="emptyrow text-right">
                                    <strong>
                                        Total: &nbsp;
                                    </strong>
                                </td>
                                <td class="highrow text-right">
                                    <strong contenteditable>$1858</strong>
                                </td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <h4><Strong>Terms and conditions:</Strong></h4>
                    <ul class="terms_conditions">
                        <li>An invoice must accompany products returned for warantty</li>
                        <li>Balance due within 10 days of invoice date,1.5% interest/month thereafter.</li>
                        <li>All goods returned for replacement/credit must be saleable condition with original
                            packaging.
                        </li>
                    </ul>
                </div>
                <div class="btn-section">
                    <div class="col-md-12 col-sm-12 col-12">
                                <span class="float-right">
                                            <button type="button"
                                                    class="btn btn-responsive button-alignment btn-success mb-3"
                                                    data-toggle="button">
                                                <i class="fa fa-fw ti-money"></i> Pay Now
                                            </button>
                                             <button type="button"
                                                     class="btn btn-responsive button-alignment btn-primary mb-3"
                                                     data-toggle="button">
                                                <span style="color:#fff;" onclick="javascript:window.print();">
                                                    <i class="fa fa-fw ti-printer"></i>
                                                Print
                                            </span>
                                </button>
                                </span>
                    </div>
                </div>
                <div class="background-overlay"></div>
            </div>
        </div>
    </section>
</template>
<script>
    export default {
        name: "invoice",
        data(){
            return{
                tr:[{itemname:"Samsung Galaxy Grand",cost:"$700",qunatity:"1",total:"$700"},
                    {itemname:" Samsung Galaxy Core",cost:"$1110",qunatity:"1",total:"$1110"},
                    {itemname:"Screen Protector",cost:"$7",qunatity:"4",total:"$28"},],
            }
        },
        methods:{
            addRow(){
                this.tr.push({});
            },
            removeRow(index){
                this.tr.splice(index,1);
            }
        },
        mounted: function() {
            "use strict";

        },
        destroyed: function() {

        }
    }
</script>
<style src="../../css/invoice.css"></style>