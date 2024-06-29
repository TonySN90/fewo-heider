import React from "react";

function InsightLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="w-[95%] max-w-[1250px] mx-auto py-[5rem]">{children}</div>
  );
}

export default InsightLayout;
