$(document).ready(function(){
    $("#loadBtn").click(function(){
        $.get("soal1.txt", function(data) {
            $("#result").html("");

            let paragraphs = data.split("-");

            $.each(paragraphs, function(index,item) {
                let text = item.trim();

                if(text !== "") {
                    let card = `
                        <div class="col-md-4 mb-3"> 
                            <div class ="card shadow">
                                <div class="card-header bg-primary text-white">
                                    Paragraf ${index + 1}
                                </div>
                                <div class="card-body">
                                    <p>${text}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    $("#result").append(card);
                }
            });
        }).fail(function() {
            alert("File gagal dimuat!");
        });
    });
});