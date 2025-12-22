function searchPostcode() {
    new daum.Postcode({
        oncomplete: function (data) {
            var roadAddr = data.roadAddress; // 도로명 주소 변수
            var jibunAddr = data.jibunAddress; // 지번 주소 변수
            var extraRoadAddr = ""; // 참고 항목 변수

            if (data.bname !== "" && /[동|로|가]$/g.test(data.bname)) {
                extraRoadAddr += data.bname;
            }
            // 건물명이 있고, 공동주택일 경우 추가한다.
            if (data.buildingName !== "" && data.apartment === "Y") {
                extraRoadAddr +=
                    extraRoadAddr !== ""
                        ? ", " + data.buildingName
                        : data.buildingName;
            }
            // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
            if (extraRoadAddr !== "") {
                extraRoadAddr = " (" + extraRoadAddr + ")";
            }

            // 우편번호와 주소 정보를 해당 필드에 넣는다.
            document.getElementById("post").value = data.zonecode;
            document.getElementById("address_basic").value = roadAddr;
            document.getElementById("address_jibun").value = jibunAddr;

            if (roadAddr !== "") {
                document.getElementById("address_local").value =
                    extraRoadAddr;
            } else {
                document.getElementById("address_local").value = "";
            }

            changeGeocoding();
        },
    }).open();
}

function changeGeocoding() {
    var addr = document.getElementById('address_basic').value;
    var geocoder = new kakao.maps.services.Geocoder();

    // 주소로 좌표를 검색합니다
    geocoder.addressSearch(addr, function (result, status) {
        // 정상적으로 검색이 완료됐으면
        if (status === kakao.maps.services.Status.OK) {
            document.getElementById('lat').value = result[0].y;
            document.getElementById('lng').value = result[0].x;
        }
    });
}
