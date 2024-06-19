import Description from "./Description";
import HouseImages from "./HouseImages";
import InsightLayout from "./InsightLayout";
import Title from "./Title";

function Insights() {
  return (
    <section className="mx-auto bg-red-50">
      <InsightLayout>
        <Title />
        <HouseImages />
        <Description />
      </InsightLayout>
    </section>
  );
}

export default Insights;
