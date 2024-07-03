function Prices() {
  return (
    <table className="text-white rounded-md overflow-hidden text-sm lg:text-[15px] w-full lg:w-2/3">
      <thead>
        <tr className=" bg-color_bg_darkgray text-[14px]">
          <th>
            Zeitraum <br />
            Saison 2024
          </th>
          <th>
            Preis 1. Nacht <br />
            inkl. Endreinigung
          </th>
          <th>Jede weitere Nacht</th>
          <th>
            Mindest- <br /> übernach- <br /> tungen
          </th>
        </tr>
      </thead>
      <tbody className="bg-color_bg_lightgray text-color_bg_darkgray font-semibold h-[150px] sm:h-[200px]">
        <tr>
          <td>09.05.–30.06.</td>
          <td>90,00 €</td>
          <td>55,00 €</td>
          <td>5</td>
        </tr>
        <tr>
          <td>01.07.–31.08.</td>
          <td>94,00 €</td>
          <td>59,00 €</td>
          <td>7</td>
        </tr>
        <tr>
          <td>01.09.–30.09.</td>
          <td>90,00 €</td>
          <td>55,00 €</td>
          <td>5</td>
        </tr>
      </tbody>
    </table>
  );
}

export default Prices;
